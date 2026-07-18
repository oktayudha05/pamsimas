<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Pencatatan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));

        // Urutkan berdasarkan dusun dulu, baru RT/RW
        $wargas = Warga::orderBy('dusun')->orderBy('rt')->orderBy('rw')->orderBy('nama')->get();
        
        $totalPemakaian = 0;
        $totalTagihan = 0;
        $totalTerbayar = 0;
        $totalHutang = 0;

        foreach ($wargas as $warga) {
            // 1. Data Bulan Ini
            $warga->pencatatan = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();

            // 2. Data Bulan Lalu (untuk Meteran Awal & Titip Lama)
            $warga->pencatatan_lalu = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', '<', $bulan)
                ->orderBy('bulan', 'desc')
                ->first();

            // 3. Rincian Meteran
            $warga->meteran_awal = $warga->pencatatan_lalu ? $warga->pencatatan_lalu->angka_meteran : 0;
            $warga->meteran_akhir = $warga->pencatatan ? $warga->pencatatan->angka_meteran : 0;
            $warga->pemakaian = $warga->pencatatan ? $warga->pencatatan->pemakaian : 0;
            
            $totalPemakaian += $warga->pemakaian;

            // 4. Rincian Tarif (Dinamis dari Model Keuangan)
            $tarif = Pembayaran::getTarifAktif($warga->dusun);
            $warga->tarif_per_meter = $tarif ? $tarif->harga_per_meter : 0;
            $warga->dana_meter = $tarif ? $tarif->dana_meter : 0;

            // 5. Kalkulasi Keuangan
            $warga->harga_air = $warga->pemakaian * $warga->tarif_per_meter;
            $warga->tagihan_bulan_ini = $warga->harga_air + $warga->dana_meter;
            
            $warga->titip_lama = $warga->pencatatan_lalu ? $warga->pencatatan_lalu->titip : 0;
            $warga->total_tagihan = $warga->tagihan_bulan_ini + $warga->titip_lama; // Kalkulasi dengan bulan sebelumnya
            
            $warga->terbayar = $warga->pencatatan ? $warga->pencatatan->dibayar : 0;
            $warga->hutang_titip = $warga->total_tagihan - $warga->terbayar; // Bisa negatif jika lebih bayar

            // 6. Akumulasi Total Keseluruhan
            $totalTagihan += $warga->total_tagihan;
            $totalTerbayar += $warga->terbayar;
            $totalHutang += max(0, $warga->hutang_titip); // Hanya hitung hutang positif untuk total
        }

        return view('rekap', compact(
            'wargas', 'bulan', 'totalPemakaian', 'totalTagihan', 'totalTerbayar', 'totalHutang'
        ));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));
        $wargas = Warga::orderBy('dusun')->orderBy('rt')->orderBy('rw')->orderBy('nama')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Bulanan');

        $namaBulan = \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y');
        
        // ===== JUDUL LAPORAN =====
        $sheet->mergeCells('A1:N1'); 
        $sheet->setCellValue('A1', 'REKAPITULASI PENGGUNAAN & TAGIHAN AIR TIRTA ANUGERAH');
        $sheet->setCellValue('B2', 'Periode: ' . $namaBulan);
        
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B2')->getFont()->setSize(11);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== HEADER TABEL =====
        $headerRow = 4;
        $headers = [
            'A' => 'No', 'B' => 'Nama Kepala Keluarga', 'C' => 'Lokasi',
            'D' => 'Meter Awal', 'E' => 'Meter Akhir', 'F' => 'Pemakaian (m³)',
            'G' => 'Tarif/m³', 'H' => 'Dana Meter', 'I' => 'Harga Air',
            'J' => 'Tagihan', 'K' => 'Titip Lama', 'L' => 'Total Tagihan',
            'M' => 'Terbayar', 'N' => 'Hutang / Titip',
        ];

        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . $headerRow, $text);
        }
        
        $sheet->getStyle("A{$headerRow}:N{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF36656B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ]);

        // ===== ISI DATA =====
        $rowNumber = $headerRow + 1;
        $no = 1;
        $totalPemakaian = 0; $totalTagihan = 0; $totalTerbayar = 0; $totalHutang = 0;

        foreach ($wargas as $warga) {
            $pencatatan = Pencatatan::where('warga_id', $warga->id)->where('bulan', $bulan)->first();
            $pencatatanLalu = Pencatatan::where('warga_id', $warga->id)->where('bulan', '<', $bulan)->orderBy('bulan', 'desc')->first();

            $meterAwal = $pencatatanLalu ? $pencatatanLalu->angka_meteran : 0;
            $meterAkhir = $pencatatan ? $pencatatan->angka_meteran : 0;
            $pemakaian = $pencatatan ? $pencatatan->pemakaian : 0;
            
            $tarif = Pembayaran::getTarifAktif($warga->dusun);
            $tarifPerMeter = $tarif ? $tarif->harga_per_meter : 0;
            $danaMeter = $tarif ? $tarif->dana_meter : 0;

            $hargaAir = $pemakaian * $tarifPerMeter;
            $tagihanBulanIni = $hargaAir + $danaMeter;
            $titipLama = $pencatatanLalu ? $pencatatanLalu->titip : 0;
            $totalTagihanRow = $tagihanBulanIni + $titipLama;
            $terbayar = $pencatatan ? $pencatatan->dibayar : 0;
            $hutangTitip = $totalTagihanRow - $terbayar;

            $totalPemakaian += $pemakaian;
            $totalTagihan += $totalTagihanRow;
            $totalTerbayar += $terbayar;
            $totalHutang += max(0, $hutangTitip);

            $lokasi = $warga->dusun === 'sragan' ? sprintf('RT %02d / RW %02d', $warga->rt, $warga->rw) : 'Luar Sragan';

            // Set Value Dasar
            $sheet->setCellValue("A{$rowNumber}", $no);
            $sheet->setCellValue("B{$rowNumber}", $warga->nama);
            $sheet->setCellValue("C{$rowNumber}", $lokasi);
            $sheet->setCellValue("D{$rowNumber}", $meterAwal);
            $sheet->setCellValue("E{$rowNumber}", $pencatatan ? $meterAkhir : '-');
            $sheet->setCellValue("F{$rowNumber}", $pemakaian);
            $sheet->setCellValue("G{$rowNumber}", $tarifPerMeter);
            $sheet->setCellValue("H{$rowNumber}", $danaMeter);
            $sheet->setCellValue("I{$rowNumber}", $hargaAir);
            $sheet->setCellValue("J{$rowNumber}", $tagihanBulanIni);
            
            $sheet->setCellValue("K{$rowNumber}", 'Rp ' . number_format(abs($titipLama), 0, ',', '.'));
            $sheet->setCellValue("L{$rowNumber}", 'Rp ' . number_format($totalTagihanRow, 0, ',', '.'));
            $sheet->setCellValue("M{$rowNumber}", 'Rp ' . number_format($terbayar, 0, ',', '.'));
            $sheet->setCellValue("N{$rowNumber}", 'Rp ' . number_format(abs($hutangTitip), 0, ',', '.'));

            // ✅ KASIH BORDER KE SELURUH KOLOM (A sampai N)
            $sheet->getStyle("A{$rowNumber}:N{$rowNumber}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
            ]);

            // Alignment Masing-Masing Kolom
            $sheet->getStyle("A{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$rowNumber}:C{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("D{$rowNumber}:E{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("F{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$rowNumber}:N{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // ✅ LOGIKA WARNA DINAMIS: TITIP LAMA (Kolom K)
            if ($titipLama > 0) {
                $sheet->getStyle("K{$rowNumber}")->getFont()->getColor()->setARGB('FFFF0000');
                $sheet->getStyle("K{$rowNumber}")->getFont()->setBold(true);
            } elseif ($titipLama < 0) {
                $sheet->getStyle("K{$rowNumber}")->getFont()->getColor()->setARGB('FF75B06F');
                $sheet->getStyle("K{$rowNumber}")->getFont()->setBold(true);
            } else {
                $sheet->getStyle("K{$rowNumber}")->getFont()->getColor()->setARGB('FF9CA3AF');
            }

            // ✅ LOGIKA WARNA DINAMIS: HUTANG / TITIP (Kolom N)
            if ($hutangTitip > 0) {
                $sheet->getStyle("N{$rowNumber}")->getFont()->getColor()->setARGB('FFFF0000');
                $sheet->getStyle("N{$rowNumber}")->getFont()->setBold(true);
            } elseif ($hutangTitip < 0) {
                $sheet->getStyle("N{$rowNumber}")->getFont()->getColor()->setARGB('FF75B06F');
                $sheet->getStyle("N{$rowNumber}")->getFont()->setBold(true);
            } else {
                $sheet->getStyle("N{$rowNumber}")->getFont()->getColor()->setARGB('FF9CA3AF');
            }

            $rowNumber++; 
            $no++;
        }
        
        // ===== BARIS TOTAL KESELURUHAN =====
        $totalRow = $rowNumber;
        $sheet->mergeCells("A{$totalRow}:C{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'TOTAL KESELURUHAN');
        $sheet->setCellValue("F{$totalRow}", $totalPemakaian . ' m³');
        
        $sheet->setCellValue("L{$totalRow}", 'Rp ' . number_format($totalTagihan, 0, ',', '.'));
        $sheet->setCellValue("M{$totalRow}", 'Rp ' . number_format($totalTerbayar, 0, ',', '.'));
        $sheet->setCellValue("N{$totalRow}", 'Rp ' . number_format($totalHutang, 0, ',', '.'));

        // ✅ BARIS TOTAL: Tanpa allBorders, cuma Top & Bottom border tipis
        $sheet->getStyle("A{$totalRow}:N{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDAD887']],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
            ],
        ]);
        
        $sheet->getStyle("A{$totalRow}:C{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("L{$totalRow}:N{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // ===== AUTO-SIZE KOLOM =====
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('B')->setWidth(25);

        // ===== KIRIM FILE KE BROWSER =====
        $writer = new Xlsx($spreadsheet);
        $filename = 'Rekap-Tagihan-' . $bulan . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
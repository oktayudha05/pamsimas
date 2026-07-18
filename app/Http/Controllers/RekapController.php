<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Pencatatan;
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

        $wargas = Warga::orderBy('rt')
            ->orderBy('rw')
            ->orderBy('nama')
            ->get();

        $totalPemakaian = 0;

        foreach ($wargas as $warga) {
            $warga->pencatatan = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();

            if ($warga->pencatatan) {
                $totalPemakaian += $warga->pencatatan->pemakaian;
            }
        }

        return view('rekap', compact('wargas', 'bulan', 'totalPemakaian'));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));

        $wargas = Warga::orderBy('rt')
            ->orderBy('rw')
            ->orderBy('nama')
            ->get();

        foreach ($wargas as $warga) {
            $warga->pencatatan = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Bulanan');

        $namaBulan = \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y');
        
        // ===== JUDUL LAPORAN =====
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'REKAPITULASI PENGGUNAAN AIR TIRTA ANUGERAH');
        $sheet->setCellValue('A2', 'Periode: ' . $namaBulan);
        
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setSize(11);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== HEADER TABEL (baris ke-4) =====
        $headerRow = 4;
        $headers = [
            'A' => 'No',
            'B' => 'Nama Kepala Keluarga',
            'C' => 'RT / RW',
            'D' => 'No. Meteran',
            'E' => 'Pemakaian (m³)',
        ];

        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . $headerRow, $text);
        }
        
        // Style header (PERBAIKI: gunakan 'argb' dengan prefix 'FF' untuk kompatibilitas versi baru)
        $sheet->getStyle("A{$headerRow}:E{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF36656B'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
            ],
        ]);

        // ===== ISI DATA =====
        $rowNumber = $headerRow + 1;
        $no = 1;
        $totalPemakaian = 0;

        foreach ($wargas as $warga) {
            $pemakaian = $warga->pencatatan ? $warga->pencatatan->pemakaian : 0;
            $totalPemakaian += $pemakaian;

            $sheet->setCellValue("A{$rowNumber}", $no);
            $sheet->setCellValue("B{$rowNumber}", $warga->nama);
            $sheet->setCellValue("C{$rowNumber}", sprintf('RT %02d / RW %02d', $warga->rt, $warga->rw));
            $sheet->setCellValue("D{$rowNumber}", $warga->nomor_meteran);
            $sheet->setCellValue("E{$rowNumber}", $pemakaian);

            // Style border dasar
            $sheet->getStyle("A{$rowNumber}:E{$rowNumber}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
                ],
            ]);
            
            // Alignment per kolom
            $sheet->getStyle("A{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // ===== PERBAIKAN UTAMA: Warna selang-seling =====
            if ($no % 2 === 0) {
                $sheet->getStyle("A{$rowNumber}:E{$rowNumber}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF0F8A4'); // Gunakan getStartColor()->setARGB()
            }

            $rowNumber++;
            $no++;
        }
        
        // ===== BARIS TOTAL =====
        $totalRow = $rowNumber;
        $sheet->mergeCells("A{$totalRow}:D{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'TOTAL PEMAKAIAN');
        $sheet->setCellValue("E{$totalRow}", $totalPemakaian);

        $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFDAD887'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== AUTO-SIZE KOLOM =====
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);

        // ===== KIRIM FILE KE BROWSER =====
        $writer = new Xlsx($spreadsheet);
        $filename = 'Rekap-Air-' . $bulan . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pencatatan;
use App\Models\Warga;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));
        $wargas = Warga::orderBy('dusun')->orderBy('rt')->orderBy('rw')->get();

        foreach ($wargas as $warga) {
            // 1. Cek data pencatatan bulan ini
            $warga->pencatatan = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();

            // 2. Cek data bulan sebelumnya (untuk menghitung titip/saldo lama)
            $warga->pencatatan_lalu = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', '<', $bulan)
                ->orderBy('bulan', 'desc')
                ->first();

            // 3. HITUNG RINCIAN TAGIHAN
            if ($warga->pencatatan) {
                $tarif = Keuangan::getTarifAktif($warga->dusun);
                
                $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
                $danaMeter = $tarif ? $tarif->dana_meter : 0;
                $pemakaian = $warga->pencatatan->pemakaian;

                // Simpan rincian ke object pencatatan agar bisa diakses di Blade
                $warga->pencatatan->harga_per_meter = $hargaMeter;
                $warga->pencatatan->dana_meter = $danaMeter;
                $warga->pencatatan->pemakaian_detail = $pemakaian; // Pakai nama beda agar tidak bentrok
                $warga->pencatatan->tagihan_bulan_ini = ($pemakaian * $hargaMeter) + $danaMeter;

                // Hitung saldo berjalan
                $warga->pencatatan->saldo_awal = $warga->pencatatan_lalu ? $warga->pencatatan_lalu->titip : 0;
                $warga->pencatatan->total_harus_dibayar = $warga->pencatatan->tagihan_bulan_ini + $warga->pencatatan->saldo_awal;
            }
        }

        return view('keuangans.index', compact('wargas', 'bulan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dibayar' => 'required|numeric|min:0',
        ]);

        $pencatatan = Pencatatan::with('warga')->findOrFail($id);
        
        // 1. Hitung Tagihan Bulan Ini
        $tarif = Keuangan::getTarifAktif($pencatatan->warga->dusun);
        $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
        $danaMeter = $tarif ? $tarif->dana_meter : 0;
        $tagihanBulanIni = ($pencatatan->pemakaian * $hargaMeter) + $danaMeter;

        // 2. Ambil Saldo Awal (dari kolom 'titip' bulan lalu)
        $pencatatanLalu = Pencatatan::where('warga_id', $pencatatan->warga_id)
            ->where('bulan', '<', $pencatatan->bulan)
            ->orderBy('bulan', 'desc')
            ->first();
            
        $saldoAwal = $pencatatanLalu ? $pencatatanLalu->titip : 0;
        
        // 3. Total Kewajiban = Tagihan Bulan Ini + Saldo Awal (bisa minus kalau ada saldo lebih)
        $totalHarusDibayar = $tagihanBulanIni + $saldoAwal;

        // 4. Hitung Sisa Saldo (Ini yang disimpan ke kolom 'titip')
        // Jika hasil minus, artinya warga punya saldo lebih (kredit) untuk bulan depan
        $dibayar = $request->dibayar;
        $sisaSaldo = $totalHarusDibayar - $dibayar;

        // 5. Update ke database
        $pencatatan->update([
            'dibayar' => $dibayar,
            'titip'   => $sisaSaldo, // Simpan sebagai running balance (bisa +, -, atau 0)
        ]);

        return redirect()->route('keuangan.index', ['bulan' => $pencatatan->bulan])
            ->with('success', 'Pembayaran berhasil dicatat. Sisa saldo: Rp ' . number_format($sisaSaldo, 0, ',', '.'));
    }
}
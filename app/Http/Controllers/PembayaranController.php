<?php

namespace App\Http\Controllers;

use App\Models\Pencatatan;
use App\Models\Warga;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));
        $search = $request->input('search');

        $wargas = Warga::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_meteran', 'like', "%{$search}%");
            })
            ->orderBy('dusun')
            ->orderBy('rt')
            ->orderBy('rw')
            ->get();

        foreach ($wargas as $warga) {
            $warga->pencatatan = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();

            $warga->pencatatan_lalu = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', '<', $bulan)
                ->orderBy('bulan', 'desc')
                ->first();

            if ($warga->pencatatan) {
                $tarif = \App\Models\Pembayaran::getTarifAktif($warga->dusun);
                
                $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
                $danaMeter = $tarif ? $tarif->dana_meter : 0;
                $pemakaian = $warga->pencatatan->pemakaian;

                $warga->pencatatan->harga_per_meter = $hargaMeter;
                $warga->pencatatan->dana_meter = $danaMeter;
                $warga->pencatatan->pemakaian_detail = $pemakaian;
                $warga->pencatatan->tagihan_bulan_ini = ($pemakaian * $hargaMeter) + $danaMeter;

                // ✅ PERBAIKAN LOGIKA SALDO AWAL
                $saldoAwal = 0;
                if ($warga->pencatatan_lalu) {
                    $saldoAwal = $warga->pencatatan_lalu->titip;
                }

                $warga->pencatatan->saldo_awal = $saldoAwal;
                $warga->pencatatan->total_harus_dibayar = $warga->pencatatan->tagihan_bulan_ini + $warga->pencatatan->saldo_awal;
            }
        }

        return view('pembayarans.index', compact('wargas', 'bulan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dibayar' => 'required|numeric|min:0',
        ]);

        $pencatatan = Pencatatan::with('warga')->findOrFail($id);
        
        $tarif = Pembayaran::getTarifAktif($pencatatan->warga->dusun);
        $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
        $danaMeter = $tarif ? $tarif->dana_meter : 0;
        $tagihanBulanIni = ($pencatatan->pemakaian * $hargaMeter) + $danaMeter;

        $pencatatanLalu = Pencatatan::where('warga_id', $pencatatan->warga_id)
            ->where('bulan', '<', $pencatatan->bulan)
            ->orderBy('bulan', 'desc')
            ->first();
            
        $saldoAwal = $pencatatanLalu ? $pencatatanLalu->titip : 0;
        
        $totalHarusDibayar = $tagihanBulanIni + $saldoAwal;

        $dibayar = $request->dibayar;
        $sisaSaldo = $totalHarusDibayar - $dibayar;

        $pencatatan->update([
            'dibayar' => $dibayar,
            'titip'   => $sisaSaldo,
        ]);

        return redirect()->route('pembayaran.index', ['bulan' => $pencatatan->bulan])
            ->with('success', 'Pembayaran berhasil dicatat. Sisa saldo: Rp ' . number_format($sisaSaldo, 0, ',', '.'));
    }
}

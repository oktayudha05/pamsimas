<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Pencatatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PencatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));

        $wargas = Warga::orderBy('rt')
            ->orderBy('rw')
            ->orderBy('nama')
            ->get();

        // Attach recording details for the selected month to each warga
        foreach ($wargas as $warga) {
            $warga->pencatatan_sekarang = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();

            // Fetch the latest recording before this month
            $warga->pencatatan_lalu = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', '<', $bulan)
                ->orderBy('bulan', 'desc')
                ->first();
        }

        return view('pencatatans.index', compact('wargas', 'bulan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warga_id' => ['required', 'exists:wargas,id'],
            'bulan' => ['required', 'date_format:Y-m'],
            'angka_meteran' => ['required', 'integer', 'min:0'],
        ]);

        $wargaId = $request->warga_id;
        $bulan = $request->bulan;
        $angkaMeteran = $request->angka_meteran;

        $exists = Pencatatan::where('warga_id', $wargaId)
            ->where('bulan', $bulan)
            ->exists();

        if ($exists) {
            return back()->withErrors(['pencatatan' => 'Data pencatatan warga ini untuk bulan ' . $bulan . ' sudah diinput.']);
        }

        $pencatatanLalu = Pencatatan::where('warga_id', $wargaId)
            ->where('bulan', '<', $bulan)
            ->orderBy('bulan', 'desc')
            ->first();

        $angkaLalu = $pencatatanLalu ? $pencatatanLalu->angka_meteran : 0;
        
        if ($angkaMeteran < $angkaLalu) {
            return back()->withErrors([
                'angka_meteran' => "Angka meteran baru ($angkaMeteran) tidak boleh lebih kecil dari angka meteran sebelumnya ($angkaLalu)."
            ])->withInput();
        }

        $pemakaian = $angkaMeteran - $angkaLalu;
        
        // ✅ PERBAIKAN: Hitung tagihan dan titip langsung saat input
        $warga = Warga::find($wargaId);
        
        // Gunakan model Pembayaran (atau Keuangan jika nama model lo adalah Keuangan)
        $tarif = \App\Models\Pembayaran::getTarifAktif($warga->dusun); 
        
        $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
        $danaMeter = $tarif ? $tarif->dana_meter : 0;
        $tagihanBulanIni = ($pemakaian * $hargaMeter) + $danaMeter;
        
        $saldoAwal = $pencatatanLalu ? $pencatatanLalu->titip : 0;
        $totalHarusDibayar = $tagihanBulanIni + $saldoAwal;

        Pencatatan::create([
            'warga_id' => $wargaId,
            'bulan' => $bulan,
            'angka_meteran' => $angkaMeteran,
            'pemakaian' => $pemakaian,
            'user_id' => Auth::id(),
            'dibayar' => 0,
            'titip' => $totalHarusDibayar, // ✅ INI KUNCINYA! Langsung simpan total tunggakan
        ]);

        return redirect()->route('pencatatans.index', ['bulan' => $bulan])
            ->with('success', 'Pencatatan meteran berhasil disimpan.');
    }
}

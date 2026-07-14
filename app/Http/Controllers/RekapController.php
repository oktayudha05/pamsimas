<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Pencatatan;
use Illuminate\Http\Request;

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
}

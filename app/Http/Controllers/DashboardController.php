<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use App\Models\Pencatatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));

        // 1. Data Stats Dasar
        $data = [
            'total_petugas' => User::where('role', 'petugas')->count(),
            'total_warga'   => Warga::count(),
            'total_meteran' => Pencatatan::where('bulan', $bulan)->sum('pemakaian'),
        ];

        // 2. TREND 6 BULAN
        $trendBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $targetBulan = Carbon::parse($bulan . '-01')->subMonths($i)->format('Y-m');
            $total = Pencatatan::where('bulan', $targetBulan)->sum('pemakaian');
            $trendBulanan[] = [
                'bulan' => Carbon::parse($targetBulan . '-01')->translatedFormat('M Y'),
                'total' => $total,
            ];
        }

        // 3. PEMAKAIAN PER RT (Bar Chart)
        $pemakaianPerRt = Warga::select('dusun', 'rt')
            ->selectRaw('SUM(CASE WHEN pencatatans.bulan = ? THEN pencatatans.pemakaian ELSE 0 END) as total', [$bulan])
            ->leftJoin('pencatatans', function($join) use ($bulan) {
                $join->on('wargas.id', '=', 'pencatatans.warga_id')
                     ->where('pencatatans.bulan', $bulan);
            })
            ->groupBy('dusun', 'rt')
            ->get()
            ->map(fn($r) => [
                // ✅ PERBAIKAN: Jika dusun luar_sragan atau rt kosong/null/0, tampilkan "Luar Sragan"
                'rt' => ($r->dusun === 'luar_sragan' || empty($r->rt)) ? 'Luar Sragan' : 'RT ' . sprintf('%02d', $r->rt),
                'total' => (int) $r->total,
            ])
            ->groupBy('rt') // Kelompokkan lagi berdasarkan label yang sudah diperbaiki
            ->map(fn($group, $rtLabel) => [
                'rt' => $rtLabel,
                'total' => $group->sum('total'),
            ])
            ->values()
            ->sortBy(function($item) {
                // Urutkan "Luar Sragan" paling belakang agar chart rapi
                return $item['rt'] === 'Luar Sragan' ? 'ZZZ' : $item['rt'];
            })
            ->values();

        // 4. DISTRIBUSI WARGA PER RT (Doughnut Chart)
        $wargaPerRt = Warga::select('dusun', 'rt', DB::raw('count(*) as total'))
            ->groupBy('dusun', 'rt')
            ->get()
            ->map(fn($r) => [
                // ✅ PERBAIKAN: Sama seperti di atas
                'rt' => ($r->dusun === 'luar_sragan' || empty($r->rt)) ? 'Luar Sragan' : 'RT ' . sprintf('%02d', $r->rt),
                'total' => $r->total,
            ])
            ->groupBy('rt')
            ->map(fn($group, $rtLabel) => [
                'rt' => $rtLabel,
                'total' => $group->sum('total'),
            ])
            ->values()
            ->sortBy(function($item) {
                return $item['rt'] === 'Luar Sragan' ? 'ZZZ' : $item['rt'];
            })
            ->values();

        // 5. TOP 5 WARGA PEMAKAI TERBANYAK
        $topWarga = Pencatatan::where('bulan', $bulan)
            ->with('warga')
            ->orderByDesc('pemakaian')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'nama' => $p->warga->nama ?? 'Unknown',
                // ✅ PERBAIKAN: Cek dusun juga di top warga
                'rt' => ($p->warga->dusun === 'luar_sragan' || empty($p->warga->rt)) ? 'Luar Sragan' : sprintf('%02d', $p->warga->rt),
                'pemakaian' => $p->pemakaian,
            ]);

        // 6. STATUS PENCATATAN
        $totalWarga = Warga::count();
        $sudahIsi = Pencatatan::where('bulan', $bulan)->count();
        $belumIsi = $totalWarga - $sudahIsi;
        $persentaseIsi = $totalWarga > 0 ? round(($sudahIsi / $totalWarga) * 100, 1) : 0;

        // 7. RATA-RATA PEMAKAIAN
        $rataRata = $totalWarga > 0 ? round($data['total_meteran'] / $totalWarga, 1) : 0;

        return view('dashboard', compact(
            'bulan',
            'data',
            'trendBulanan',
            'pemakaianPerRt',
            'wargaPerRt',
            'topWarga',
            'sudahIsi',
            'belumIsi',
            'persentaseIsi',
            'rataRata'
        ));
    }
}
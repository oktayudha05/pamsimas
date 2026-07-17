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
        // 1. Ambil bulan yang dipilih, default ke bulan ini
        $bulan = $request->input('bulan', date('Y-m'));

        // 2. Data Stats Dasar (berdasarkan $bulan)
        $data = [
            'total_petugas' => User::where('role', 'petugas')->count(),
            'total_warga'   => Warga::count(),
            'total_meteran' => Pencatatan::where('bulan', $bulan)->sum('pemakaian'),
        ];

        // 3. TREND 6 BULAN (Berakhir di bulan yang dipilih)
        $trendBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $targetBulan = Carbon::parse($bulan . '-01')->subMonths($i)->format('Y-m');
            $total = Pencatatan::where('bulan', $targetBulan)->sum('pemakaian');
            $trendBulanan[] = [
                'bulan' => Carbon::parse($targetBulan . '-01')->translatedFormat('M Y'),
                'total' => $total,
            ];
        }

        // 4. PEMAKAIAN PER RT (Bar Chart) - berdasarkan $bulan
        $pemakaianPerRt = Warga::select('rt')
            ->selectRaw('SUM(CASE WHEN pencatatans.bulan = ? THEN pencatatans.pemakaian ELSE 0 END) as total', [$bulan])
            ->leftJoin('pencatatans', function($join) use ($bulan) {
                $join->on('wargas.id', '=', 'pencatatans.warga_id')
                     ->where('pencatatans.bulan', $bulan);
            })
            ->groupBy('rt')
            ->orderBy('rt')
            ->get()
            ->map(fn($r) => [
                'rt' => 'RT ' . sprintf('%02d', $r->rt),
                'total' => (int) $r->total,
            ]);

        // 5. DISTRIBUSI WARGA PER RT (Doughnut Chart) - Statis (tidak bergantung bulan)
        $wargaPerRt = Warga::select('rt', DB::raw('count(*) as total'))
            ->groupBy('rt')
            ->orderBy('rt')
            ->get()
            ->map(fn($r) => [
                'rt' => 'RT ' . sprintf('%02d', $r->rt),
                'total' => $r->total,
            ]);

        // 6. TOP 5 WARGA PEMAKAI TERBANYAK - berdasarkan $bulan
        $topWarga = Pencatatan::where('bulan', $bulan)
            ->with('warga')
            ->orderByDesc('pemakaian')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'nama' => $p->warga->nama ?? 'Unknown',
                'rt' => $p->warga ? sprintf('%02d', $p->warga->rt) : '-',
                'pemakaian' => $p->pemakaian,
            ]);

        // 7. STATUS PENCATATAN - berdasarkan $bulan
        $totalWarga = Warga::count();
        $sudahIsi = Pencatatan::where('bulan', $bulan)->count();
        $belumIsi = $totalWarga - $sudahIsi;
        $persentaseIsi = $totalWarga > 0 ? round(($sudahIsi / $totalWarga) * 100, 1) : 0;

        // 8. RATA-RATA PEMAKAIAN
        $rataRata = $totalWarga > 0 ? round($data['total_meteran'] / $totalWarga, 1) : 0;

        return view('dashboard', compact(
            'bulan', // <-- PENTING: kirim ke view
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
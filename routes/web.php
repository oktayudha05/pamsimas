<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PencatatanController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PembayaranController;
use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Pencatatan;

Route::get('/', function () {
    // 1. Data Statistik Dasar
    $stats = [
        'total_warga' => Warga::count(),
        'total_pemakaian_bulan_ini' => Pencatatan::where('bulan', date('Y-m'))->sum('pemakaian'),
        'status_layanan' => 'Aktif & Terlayani',
    ];

    // 2. Data Tren 6 Bulan (untuk Line Chart)
    $trendBulanan = [];
    for ($i = 5; $i >= 0; $i--) {
        $targetBulan = Carbon::now()->subMonths($i)->format('Y-m');
        $total = Pencatatan::where('bulan', $targetBulan)->sum('pemakaian');
        $trendBulanan[] = [
            'bulan' => Carbon::parse($targetBulan . '-01')->translatedFormat('M Y'),
            'total' => $total,
        ];
    }

    // 3. Data Distribusi per Dusun (untuk Doughnut Chart)
    $wargaPerDusun = Warga::select('dusun', DB::raw('count(*) as total'))
        ->groupBy('dusun')
        ->get()
        ->map(fn($r) => [
            'label' => $r->dusun === 'sragan' ? 'Dusun Sragan' : 'Luar Sragan',
            'total' => $r->total,
        ]);

    // Kirim semua data ke view welcome
    return view('welcome', compact('stats', 'trendBulanan', 'wargaPerDusun'));
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pencatatans', PencatatanController::class)->only(['index', 'store']);
});

Route::middleware(['auth', 'role:pengelola'])->group(function () {
    Route::resource('wargas', WargaController::class)->except(['create', 'show']);
    Route::resource('akuns', AkunController::class)->except(['show']);
    
    Route::get('rekap/excel', [RekapController::class, 'exportExcel'])->name('rekap.excel');
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
});

Route::middleware(['auth', 'role:pengelola,petugas'])->group(function () {
    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::patch('pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

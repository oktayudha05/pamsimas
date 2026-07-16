<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PencatatanController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\PetugasController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pencatatans', PencatatanController::class)->only(['index', 'store']);
});

Route::middleware(['auth', 'role:pengelola'])->group(function () {
    Route::resource('wargas', WargaController::class)->except(['create', 'show']);
    Route::resource('petugases', PetugasController::class)->except(['show']);
    Route::get('rekap/excel', [RekapController::class, 'exportExcel'])->name('rekap.excel');
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

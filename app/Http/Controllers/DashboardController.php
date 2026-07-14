<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use App\Models\Pencatatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_petugas' => User::where('role', 'petugas')->count(),
            'total_warga'   => Warga::count(),
            'total_meteran' => Pencatatan::where('bulan', date('Y-m'))->sum('pemakaian'),
        ];

        return view('dashboard', compact('data'));
    }
}
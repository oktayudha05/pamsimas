<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keuangan;

class KeuanganSeeder extends Seeder
{
    public function run(): void
    {
        // Tarif untuk Dusun Sragan
        Keuangan::create([
            'dusun' => 'sragan',
            'harga_per_meter' => 500,
            'dana_meter' => 3000,
            'is_active' => true,
        ]);

        // Tarif untuk Luar Dusun Sragan
        Keuangan::create([
            'dusun' => 'luar_sragan',
            'harga_per_meter' => 750,
            'dana_meter' => 3500,
            'is_active' => true,
        ]);
    }
}
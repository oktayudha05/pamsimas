<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        Pembayaran::create([
            'dusun' => 'sragan',
            'harga_per_meter' => 500,
            'dana_meter' => 3000,
            'is_active' => true,
        ]);

        Pembayaran::create([
            'dusun' => 'luar_sragan',
            'harga_per_meter' => 750,
            'dana_meter' => 3500,
            'is_active' => true,
        ]);
    }
}

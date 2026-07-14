<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'oring (dev)',
            'username' => 'oring',
            'role' => 'pengelola',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'nama' => 'ronaan (petugas)',
            'username' => 'ronaan',
            'role' => 'petugas',
            'password' => Hash::make('123'),
        ]);
    }
}

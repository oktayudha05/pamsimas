<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->enum('dusun', ['sragan', 'luar_sragan']);
            $table->integer('harga_per_meter'); // Contoh: 500 atau 750
            $table->integer('dana_meter');      // Contoh: 3000 atau 3500
            $table->boolean('is_active')->default(true); // Agar bisa ada riwayat harga lama
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
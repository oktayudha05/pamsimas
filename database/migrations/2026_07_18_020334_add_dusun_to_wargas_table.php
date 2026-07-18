<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            // Tambah kolom dusun, default 'sragan' biar data lama ga error
            $table->enum('dusun', ['sragan', 'luar_sragan'])
                  ->default('sragan')
                  ->after('nama');
            
            // Bikin rt & rw jadi nullable (karena luar sragan ga perlu)
            $table->string('rt')->nullable()->change();
            $table->string('rw')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->dropColumn('dusun');
            // Kembalikan rt & rw ke not-null
            $table->string('rt')->nullable(false)->change();
            $table->string('rw')->nullable(false)->change();
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pencatatans', function (Blueprint $table) {
            $table->integer('dibayar')->default(0)->after('pemakaian');
            $table->integer('titip')->default(0)->after('dibayar');
        });
    }

    public function down(): void
    {
        Schema::table('pencatatans', function (Blueprint $table) {
            $table->dropColumn(['dibayar', 'titip']);
        });
    }
};
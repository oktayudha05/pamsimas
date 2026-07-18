<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $fillable = [
        'dusun',
        'harga_per_meter',
        'dana_meter',
        'is_active',
    ];

    /**
     * Ambil tarif yang sedang aktif berdasarkan dusun
     */
    public static function getTarifAktif($dusun)
    {
        return self::where('dusun', $dusun)
                   ->where('is_active', true)
                   ->first();
    }
}
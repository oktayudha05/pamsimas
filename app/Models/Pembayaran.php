<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = [
        'dusun',
        'harga_per_meter',
        'dana_meter',
        'is_active',
    ];

    public static function getTarifAktif($dusun)
    {
        return self::where('dusun', $dusun)
                   ->where('is_active', true)
                   ->first();
    }
}

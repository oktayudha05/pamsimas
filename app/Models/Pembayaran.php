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
        'berlaku_mulai',
        'is_active',
    ];

    public static function getTarifAktif($dusun, $bulan = null)
    {
        if (!$bulan) {
            $bulan = date('Y-m');
        }

        // Ambil tarif terbaru yang sudah berlaku pada atau sebelum bulan yang ditentukan
        return self::where('dusun', $dusun)
                   ->where('berlaku_mulai', '<=', $bulan)
                   ->orderBy('berlaku_mulai', 'desc')
                   ->first();
    }
}

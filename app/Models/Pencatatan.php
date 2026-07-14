<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pencatatan extends Model
{
    protected $fillable = [
        'warga_id',
        'bulan',
        'angka_meteran',
        'pemakaian',
        'user_id',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

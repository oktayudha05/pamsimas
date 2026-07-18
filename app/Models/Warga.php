<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $fillable = [
        'nama',
        'dusun',
        'rt',
        'rw',
        'nomor_meteran',
    ];

    public function pencatatans()
    {
        return $this->hasMany(Pencatatan::class);
    }
}

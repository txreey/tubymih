<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{

    protected $table = 'meja';  

    protected $fillable = [
        'no_meja',
        'tipe_meja',
        'kapasitas',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'status'    => 'string',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_meja');
    }
}
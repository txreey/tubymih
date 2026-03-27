<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;   // optional

class Meja extends Model
{
    // use SoftDeletes;   // optional

    protected $table = 'meja';   // ← penting: singular 'meja'

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
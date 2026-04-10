<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'id_kasir',
        'id_meja',
        'no_transaksi',
        'jenis_pemesanan',
        'nama_pelanggan',
        'tanggal',
        'waktu_pemesanan',
        'total_harga',
        'jumlah_bayar',
        'kembalian',
        'status',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'id_meja');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}

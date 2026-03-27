<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama', 'username', 'password',
        'no_hp', 'alamat', 'role', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kasir');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'id_user');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $fillable = ['id_user', 'aktivitas', 'waktu'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
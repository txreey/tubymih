<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama'     => 'Dalendra Rasel',
                'username' => 'kasir',
                'password' => Hash::make('kasir123'),
                'no_hp'    => '081208736537',
                'role'     => 'kasir',
                'alamat'   => 'Jl. Kasir Satu No. 1',
                'status'   => 'aktif',
            ],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(['username' => $u['username']], $u);
        }
    }
}
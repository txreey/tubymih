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
                'nama'     => 'Owner Mimih',
                'username' => 'owner',
                'password' => Hash::make('owner123'),
                'no_hp'    => '081200000003',
                'role'     => 'owner',
                'alamat'   => null,
                'status'   => 'aktif',
            ],
            [
                'nama'     => 'Sugiharto',
                'username' => 'sumin',
                'password' => Hash::make('sumin333'),
                'no_hp'    => '089767652345',
                'role'     => 'admin',
                'alamat'   => 'Jl. Magefy',
                'status'   => 'aktif',
            ],
            [
                'nama'     => 'Dalendra',
                'username' => 'Dasir',
                'password' => Hash::make('dasir333'),
                'no_hp'    => '234567876543',
                'role'     => 'kasir',
                'alamat'   => 'Jl. Raya Bandung No.Km 12 41211 Jawa Barat',
                'status'   => 'aktif',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['username' => $u['username']],
                $u
            );
        }
    }
}
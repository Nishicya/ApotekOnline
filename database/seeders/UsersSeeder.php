<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
    {
        $users = [
            [
                'name' => 'Mona-Admin',
                'email' => 'admin@gmail.com',
                'no_hp' => '088210409349',
                'password' => Hash::make('123123'),
                'role' => 'admin',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jono',
                'email' => 'karyawan@gmail.com',
                'no_hp' => '089765432134',
                'password' => Hash::make('123123'),
                'role' => 'karyawan',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jeje',
                'email' => 'apoteker@gmail.com',
                'no_hp' => '081234349093',
                'password' => Hash::make('123123'),
                'role' => 'apoteker',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mbak Kasir',
                'email' => 'kasir@gmail.com',
                'no_hp' => '084567890123',
                'password' => Hash::make('123123'),
                'role' => 'kasir',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Klee',
                'email' => 'owner@gmail.com',
                'no_hp' => '085678901234',
                'password' => Hash::make('123123'),
                'role' => 'pemilik',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}

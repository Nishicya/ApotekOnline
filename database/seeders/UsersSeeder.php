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
            [
                'name' => 'Pak bambang',
                'email' => 'kurir@gmail.com',
                'no_hp' => '085678901235',
                'password' => Hash::make('123123'),
                'role' => 'kurir',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kurir A - JNT',
                'email' => 'kurir_jnt@gmail.com',
                'no_hp' => '08123456789',
                'password' => Hash::make('123123'),
                'role' => 'kurir',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kurir B - JNE',
                'email' => 'kurir_jne@gmail.com',
                'no_hp' => '08234567890',
                'password' => Hash::make('123123'),
                'role' => 'kurir',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kurir C - Grab',
                'email' => 'kurir_grab@gmail.com',
                'no_hp' => '08345678901',
                'password' => Hash::make('123123'),
                'role' => 'kurir',
                'foto' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}

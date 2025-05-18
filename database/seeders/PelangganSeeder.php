<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pelanggans = [
            [
                'nama_pelanggan' => 'Xiaomo',
                'email' => 'maomao@gmail.com',
                'password' => Hash::make('123123'),
                'no_hp' => '081234567890',
                'alamat1' => 'Jl. Merdeka No. 123',
                'kota1' => 'Bandung',
                'propinsi1' => 'Jawa Barat',
                'kodepos1' => '40111',
                'alamat2' => 'Jl. Sudirman No. 456',
                'kota2' => 'Jakarta',
                'propinsi2' => 'DKI Jakarta',
                'kodepos2' => '12190',
                'alamat3' => 'Jl. Gatot Subroto No. 789',
                'kota3' => 'Semarang',
                'propinsi3' => 'Jawa Tengah',
                'kodepos3' => '50241',
                'foto' => '',
                'url_ktp' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pelanggan' => 'Yae',
                'email' => 'iloveraiden@gmail.com',
                'password' => Hash::make('123123'),
                'no_hp' => '082345678901',
                'alamat1' => 'Jl. Asia Afrika No. 10',
                'kota1' => 'Bandung',
                'propinsi1' => 'Jawa Barat',
                'kodepos1' => '40112',
                'alamat2' => 'Jl. Thamrin No. 20',
                'kota2' => 'Jakarta',
                'propinsi2' => 'DKI Jakarta',
                'kodepos2' => '10350',
                'alamat3' => 'Jl. Pahlawan No. 30',
                'kota3' => 'Surabaya',
                'propinsi3' => 'Jawa Timur',
                'kodepos3' => '60241',
                'foto' => '',
                'url_ktp' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pelanggans')->insert($pelanggans);
    }
}

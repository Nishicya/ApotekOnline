<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pelanggans = [
            [
                'name_pelanggan' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'no_fp' => '081234567890',
                'alamat1' => 'Jl. Merdeka No. 123',
                'total' => '500000',
                'propins1' => 'Jawa Barat',
                'kodepos1' => '40111',
                'alamat2' => 'Jl. Sudirman No. 456',
                'total2' => '750000',
                'propins2' => 'DKI Jakarta',
                'kodepos2' => '12190',
                'alamat3' => 'Jl. Gatot Subroto No. 789',
                'total3' => '1000000',
                'propins3' => 'Jawa Tengah',
                'kodepos3' => '50241',
                'foto' => 'profile1.jpg',
                'url_idp' => 'john-doe-profile',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_pelanggan' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password456'),
                'no_fp' => '082345678901',
                'alamat1' => 'Jl. Asia Afrika No. 10',
                'total' => '300000',
                'propins1' => 'Jawa Barat',
                'kodepos1' => '40112',
                'alamat2' => 'Jl. Thamrin No. 20',
                'total2' => '450000',
                'propins2' => 'DKI Jakarta',
                'kodepos2' => '10350',
                'alamat3' => 'Jl. Pahlawan No. 30',
                'total3' => '600000',
                'propins3' => 'Jawa Timur',
                'kodepos3' => '60241',
                'foto' => 'profile2.jpg',
                'url_idp' => 'jane-smith-profile',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_pelanggan' => 'Robert Johnson',
                'email' => 'robert.j@example.com',
                'password' => Hash::make('password789'),
                'no_fp' => '083456789012',
                'alamat1' => 'Jl. Diponegoro No. 5',
                'total' => '400000',
                'propins1' => 'Jawa Tengah',
                'kodepos1' => '50123',
                'alamat2' => 'Jl. Hayam Wuruk No. 15',
                'total2' => '550000',
                'propins2' => 'DKI Jakarta',
                'kodepos2' => '11160',
                'alamat3' => 'Jl. Ahmad Yani No. 25',
                'total3' => '700000',
                'propins3' => 'Jawa Timur',
                'kodepos3' => '60231',
                'foto' => 'profile3.jpg',
                'url_idp' => 'robert-johnson-profile',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('spolek_online_pelanggan')->insert($pelanggans);
    }
}

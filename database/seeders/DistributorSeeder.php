<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
    {
        $distributors = [
            [
                'nama_distributor' => 'PT Anugerah Pharmindo Lestari (APL)',
                'telepon' => '4516066-75-76',
                'alamat' => 'Jalan Boulevard BGR No. 1, Komplek Pergudangan BGR Gudang M, Kelapa Gading, Jakarta 14240, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_distributor' => 'PT Merapi Utama Pharma',
                'telepon' => '+62-21-3141906',
                'alamat' => 'PT MERAPI UTAMA PHARMA Jl. Cilosari No. 25, Cikini, Jakarta Pusat - 10330',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_distributor' => 'PT Enseval Putera Megatrading',
                'telepon' => '(021) 46822422',
                'alamat' => 'Head Office Jl. Pulo Lentut No. 10. Kawasan Industri Pulo Gadung Jakarta 13920, Indonesia Customer Hotline : 1500095',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_distributor' => 'PT Anugrah Argon Medica',
                'telepon' => '0811-9791-338',
                'alamat' => 'Titan Center, lantai 4, Jl. Boulevard Bintaro, Blok B7/B1 No.5, Bintaro Jaya Sektor 7, Tangerang-Indonesia 15424',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('distributors')->insert($distributors);
    }
}

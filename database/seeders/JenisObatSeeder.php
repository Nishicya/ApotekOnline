<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
        {
        $jenisObats = [
            [
                'jenis' => 'Obat Bebas',
                'deskripsi_jenis' => 'obat yang dijual bebas di pasaran dan dapat dibeli tanpa resep dokter.',
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Obat Bebas Terbatas',
                'deskripsi_jenis' => 'obat yang dapat dibeli secara bebas tanpa menggunakan resep dokter, namun mempunyai peringatan khusus saat menggunakannya.',
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Obat Keras',
                'deskripsi_jenis' => 'obat yang hanya boleh dibeli menggunakan resep dokter.',
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Obat Herbal',
                'deskripsi_jenis' => 'obat yang terbuat dari bahan alami, terutama tumbuhan, yang digunakan untuk mengobati berbagai penyakit atau gangguan kesehatan.',
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('jenis_obats')->insert($jenisObats);
    }
}

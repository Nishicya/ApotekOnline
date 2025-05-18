<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $obats = [
            // Obat Bebas examples
            [
                'nama_obat' => 'Promag 1 pack',
                'id_jenis' => 1, // Obat Bebas
                'harga_jual' => 25000,
                'deskripsi_obat' => 'mengurangi gejala-gejala yang berhubungan dengan kelebihan asam lambung, gastritis, tukak lambung, tukak usus 12 jari.',
                'foto1' => '',
                'foto2' => null,
                'foto3' => null,
                'stok' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_obat' => 'Promag 1 Strip',
                'id_jenis' => 1, // Obat Bebas
                'harga_jual' => 8000,
                'deskripsi_obat' => 'mengurangi gejala-gejala yang berhubungan dengan kelebihan asam lambung, gastritis, tukak lambung, tukak usus 12 jari.',
                'foto1' => '',
                'foto2' => null,
                'foto3' => null,
                'stok' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Obat Bebas Terbatas examples
            [
                'nama_obat' => 'Panadol Reguler',
                'id_jenis' => 1, // Obat Bebas Terbatas
                'harga_jual' => 14000,
                'deskripsi_obat' => 'meredakan nyeri, seperti sakit kepala, sakit gigi, dan nyeri otot, serta menurunkan demam.',
                'foto1' => '',
                'foto2' => null,
                'foto3' => null,
                'stok' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Obat Keras examples
            [
                'nama_obat' => 'Amoxicillin 500mg',
                'id_jenis' => 3, // Obat Keras
                'harga_jual' => 25000,
                'deskripsi_obat' => 'Untuk mengatasi infeksi bakteri. Perlu resep dokter.',
                'foto1' => '',
                'foto2' => null,
                'foto3' => null,
                'stok' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Obat Herbal examples
            [
                'nama_obat' => 'Panadol Cold & Flu',
                'id_jenis' => 1, // Obat Herbal
                'harga_jual' => 18000,
                'deskripsi_obat' => 'meredakan hidung tersumbat, batuk tidak berdahak, serta demam yang disebabkan oleh flu.',
                'foto1' => '',
                'foto2' => null,
                'foto3' => null,
                'stok' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('obats')->insert($obats);
    }
}

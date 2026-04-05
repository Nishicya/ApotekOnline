<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Cek apakah data sudah ada
        if (DB::table('jenis_pengirimans')->count() > 0) {
            $this->command->info('Jenis pengiriman sudah ada di database. Skip seeding.');
            return;
        }

        $jenisPengiriman = [
            [
                'jenis_kirim' => 'ekonomi',
                'nama_ekspedisi' => 'JNT Express',
                'logo_ekspedisi' => 'https://jnt.co.id/assets/img/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_kirim' => 'ekonomi',
                'nama_ekspedisi' => 'JNE Express',
                'logo_ekspedisi' => 'https://www.jne.co.id/media/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_kirim' => 'regular',
                'nama_ekspedisi' => 'Grab Express',
                'logo_ekspedisi' => 'https://grab.com/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_kirim' => 'same day',
                'nama_ekspedisi' => 'GoSend',
                'logo_ekspedisi' => 'https://gojek.com/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_kirim' => 'standar',
                'nama_ekspedisi' => 'Pos Indonesia',
                'logo_ekspedisi' => 'https://posindonesia.co.id/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_kirim' => 'regular',
                'nama_ekspedisi' => 'TIKI Express',
                'logo_ekspedisi' => 'https://tiki.id/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_kirim' => 'kargo',
                'nama_ekspedisi' => 'SiCepat Express',
                'logo_ekspedisi' => 'https://sicepat.com/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('jenis_pengirimans')->insert($jenisPengiriman);
        $this->command->info('Jenis pengiriman berhasil di-seed.');
    }
}

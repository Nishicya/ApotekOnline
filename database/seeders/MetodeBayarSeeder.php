<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodeBayarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $metode_bayars = [
            [
                'metode_pembayaran' => 'Midtrans',
                'tempat_bayar' => null,
                'no_rekening' => null,
                'url_logo' => 'https://midtrans.com/assets/img/logo-midtrans.png',
                'payment_gateway' => 'midtrans',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'metode_pembayaran' => 'Transfer Bank BCA',
                'tempat_bayar' => 'Bank Central Asia',
                'no_rekening' => '1234567890',
                'url_logo' => 'images/bca.png',
                'payment_gateway' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('metode_bayars')->insert($metode_bayars);
    }
}

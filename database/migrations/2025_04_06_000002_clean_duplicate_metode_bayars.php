<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key constraints temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus semua data metode pembayar yang duplikat
        DB::table('metode_bayars')->delete();
        
        // Insert data bersih tanpa duplikat
        DB::table('metode_bayars')->insert([
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
        ]);
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Disable foreign key constraints temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('metode_bayars')->delete();
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};

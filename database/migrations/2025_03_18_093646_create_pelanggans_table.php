<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('no_hp', 15);
            $table->string('alamat1', 255);
            $table->string('kota1', 255);
            $table->string('propinsi1', 255);
            $table->string('kodepos1', 255); 
            $table->string('alamat2', 255)->nullable();
            $table->string('kota2', 255)->nullable();
            $table->string('propinsi2', 255)->nullable();
            $table->string('kodepos2', 255)->nullable();
            $table->string('alamat3', 255)->nullable();
            $table->string('kota3', 255)->nullable();
            $table->string('propinsi3', 255)->nullable();
            $table->string('kodepos3', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('url_ktp', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};

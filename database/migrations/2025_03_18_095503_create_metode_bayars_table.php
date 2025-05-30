<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metode_bayars', function (Blueprint $table) {
            $table->id();
            $table->string('metode_pembayaran');
            $table->string('tempat_bayar', 50)->nullable();
            $table->string('no_rekening', 25)->nullable();
            $table->text('url_logo')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->text('config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode_bayars');
    }
};

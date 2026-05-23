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
        Schema::create('paket_pengadaan', function (Blueprint $table) {
            $table->id('paket_id');
            $table->string('nama_paket');
            $table->string('metode_pengadaan')->nullable();
            $table->string('opd_pemilik')->nullable();
            $table->decimal('pagu_anggaran', 15, 2)->nullable();
            $table->decimal('realisasi_kontrak', 15, 2)->nullable();
            $table->string('waktu_pelaksanaan')->nullable();
            $table->string('sumber_data')->nullable();
            $table->string('id_referensi_eksternal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_pengadaan');
    }
};

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
        Schema::create('penugasan_personil', function (Blueprint $table) {
            $table->id('penugasan_id');
            $table->unsignedBigInteger('paket_id');
            $table->unsignedBigInteger('personil_id');
            $table->date('tanggal_mulai_tugas')->nullable();
            $table->date('tanggal_selesai_tugas')->nullable();
            $table->timestamps();

            $table->foreign('paket_id')->references('paket_id')->on('paket_pengadaan')->onDelete('cascade');
            $table->foreign('personil_id')->references('personil_id')->on('personil')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_personil');
    }
};

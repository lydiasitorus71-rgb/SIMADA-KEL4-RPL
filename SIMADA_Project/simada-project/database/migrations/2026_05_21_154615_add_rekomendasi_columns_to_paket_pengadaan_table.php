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
        Schema::table('paket_pengadaan', function (Blueprint $table) {
            $table->string('rekomendasi_pemenang')->nullable();
            $table->decimal('harga_penawaran', 20, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_pengadaan', function (Blueprint $table) {
            $table->dropColumn(['rekomendasi_pemenang', 'harga_penawaran']);
        });
    }
};

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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('nomor_lapangan');
            $table->string('nama_penyewa');
            $table->date('tanggal_sewa');
            $table->smallInteger('jam_awal_sewa');
            $table->smallInteger('jam_akhir_sewa');
            $table->decimal('harga_sewa', 12, 0);
            $table->decimal('total_harga_sewa', 12, 0);
            $table->tinyInteger('status')->comment('1= Tersedia, 2= Dipesan, 3= Selesai, 4= Dibatalkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

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
        Schema::create('kehadiran_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('kehadiranKegiatanID');
            $table->unsignedBigInteger('jadwalKegiatanID');
            $table->string('pengisi_kehadiran_kegiatan');

            // Mendefinisikan foreign key 
            $table->foreign('jadwalKegiatanID')->references('jadwalKegiatanID')->on('jadwal_kegiatan')->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_kegiatan');
    }
};
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
        Schema::create('kehadiran_kegiatan_anggota', function (Blueprint $table) {
            $table->unsignedBigInteger('anggotaID');
            $table->unsignedBigInteger('kehadiranKegiatanID');
            $table->boolean('status_hadir')->default(false);

            // mendefinisikan foreign key
            $table->foreign('anggotaID')->references('anggotaID')->on('anggota')->onDelete('restrict');
            $table->foreign('kehadiranKegiatanID')->references('kehadiranKegiatanID')->on('kehadiran_kegiatan')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['anggotaID', 'kehadiranKegiatanID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_kegiatan_anggota');
    }
};
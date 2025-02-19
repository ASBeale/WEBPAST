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
        Schema::create('jadwal_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('jadwalKegiatanID'); 
            $table->string('judul_kegiatan'); 
            $table->unsignedBigInteger('jenisKegiatanID');
            $table->dateTime('tanggal_jam_mulai_kegiatan');
            $table->dateTime('tanggal_jam_selesai_kegiatan');
            $table->timestamps();

            // mendefinisikan foreign key
            $table->foreign('jenisKegiatanID')->references('jenisKegiatanID')->on('jenis_kegiatan')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kegiatan');
    }
};
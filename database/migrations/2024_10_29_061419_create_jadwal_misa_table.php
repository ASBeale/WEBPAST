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
        Schema::create('jadwal_misa', function (Blueprint $table) {
            $table->bigIncrements('jadwalMisaID'); 
            $table->unsignedBigInteger('kelompokID');
            $table->unsignedBigInteger('jenisMisaID');
            $table->unsignedBigInteger('periodeID');
            $table->dateTime('tanggal_jam_misa');
            $table->timestamps();

            // Mendefinisikan foreign key
            $table->foreign('kelompokID')->references('kelompokID')->on('kelompok')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('jenisMisaID')->references('jenisMisaID')->on('jenis_misa')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('periodeID')->references('periodeID')->on('periode')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_misa');
    }
};
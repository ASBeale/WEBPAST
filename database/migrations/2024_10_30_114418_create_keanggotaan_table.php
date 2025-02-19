<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keanggotaan', function (Blueprint $table) {
            $table->bigIncrements('keanggotaanID');
            $table->unsignedBigInteger('anggotaID');
            $table->unsignedBigInteger('kelompokID');
            $table->unsignedBigInteger('jabatanID')->nullable();
            $table->unsignedBigInteger('periodeID');
            $table->timestamps();

            $table->foreign('anggotaID')->references('anggotaID')->on('anggota')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('kelompokID')->references('kelompokID')->on('kelompok')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('jabatanID')->references('jabatanID')->on('jabatan')->onDelete('restrict'); 
            $table->foreign('periodeID')->references('periodeID')->on('periode')->onDelete('restrict'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keanggotaan');
    }
};
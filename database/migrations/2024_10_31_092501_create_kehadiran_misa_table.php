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
        Schema::create('kehadiran_misa', function (Blueprint $table) {
            $table->bigIncrements('kehadiranMisaID');
            $table->unsignedBigInteger('jadwalMisaID')->unique();
            $table->string('pengisi_kehadiran_misa');
            $table->timestamps();

            // mendefinisikan foreign key 
            $table->foreign('jadwalMisaID')->references('jadwalMisaID')->on('jadwal_misa')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_misa');
    }
};
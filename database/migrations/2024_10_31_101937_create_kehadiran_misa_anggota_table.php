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
        Schema::create('kehadiran_misa_anggota', function (Blueprint $table) {
            $table->unsignedBigInteger('keanggotaanID');
            $table->unsignedBigInteger('kehadiranMisaID');
            $table->string('sebagai');
            $table->enum('status_kehadiran', ['hadir', 'ijin', 'alpha'])->nullable();
            $table->text('alasan_ijin')->nullable();

            // mendefinisikan foreign key
            $table->foreign('keanggotaanID')->references('keanggotaanID')->on('keanggotaan')->onDelete('restrict');
            $table->foreign('kehadiranMisaID')->references('kehadiranMisaID')->on('kehadiran_misa')->onDelete('cascade');

            $table->timestamps();

            // Primary key gabungan
            $table->primary(['keanggotaanID', 'kehadiranMisaID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_misa_anggota');
    }
};
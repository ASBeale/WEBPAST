<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->bigIncrements('periodeID');
            $table->string('nama_periode');
            $table->date('tanggal_mulai_periode');
            $table->date('tanggal_selesai_periode');
            $table->boolean('status_periode')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};
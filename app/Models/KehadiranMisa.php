<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranMisa extends Model
{
    use HasFactory;

    protected $primaryKey = 'kehadiranMisaID';
    protected $table = 'kehadiran_misa';

    protected $fillable = [
        'jadwalMisaID',
        'pengisi_kehadiran_misa'
    ];

    // One to One dengan JadwalMisa
    public function jadwalMisa() {
        return $this->belongsTo(JadwalMisa::class, 'jadwalMisaID', 'jadwalMisaID'); 
    }

    // One to Many dengan KehadiranMisaAnggota
    public function kehadiranMisaAnggota() {
        return $this->hasMany(KehadiranMisaAnggota::class, 'kehadiranMisaID', 'kehadiranMisaID');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranKegiatan extends Model
{
    use HasFactory;

    protected $primaryKey = 'kehadiranKegiatanID';
    protected $table = 'kehadiran_kegiatan';

    protected $fillable = [
        'jadwalKegiatanID',
        'pengisi_kehadiran_kegiatan'
    ];

    // One to One dengan JadwalKegiatan
    public function jadwalKegiatan() {
        return $this->belongsTo(JadwalKegiatan::class, 'jadwalKegiatanID', 'jadwalKegiatanID');
    }

    // One to Many dengan KehadiranKegiatanAnggota
    public function kehadiranKegiatanAnggota() {
        return $this->hasMany(KehadiranKegiatanAnggota::class, 'kehadiranKegiatanID', 'kehadiranKegiatanID');
    }
}
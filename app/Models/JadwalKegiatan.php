<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kegiatan';
    protected $primaryKey = 'jadwalKegiatanID'; 

    protected $fillable = [
        'judul_kegiatan', 
        'jenisKegiatanID', 
        'tanggal_jam_mulai_kegiatan', 
        'tanggal_jam_selesai_kegiatan', 
    ];

    // Many to One dengan JenisKegiatan
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenisKegiatanID', 'jenisKegiatanID');
    }

    // One to One dengan KehadiranKegiatan
    public function kehadiranKegiatan()
    {
        return $this->hasOne(KehadiranKegiatan::class, 'jadwalKegiatanID', 'jadwalKegiatanID');
    }
}
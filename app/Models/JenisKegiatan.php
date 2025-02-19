<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'jenisKegiatanID';
    protected $table = 'jenis_kegiatan'; 

    protected $fillable = ['jenis_kegiatan']; 

    // One to Many dengan JadwalKegiatan
    public function jadwalKegiatan()
    {
        return $this->hasMany(JadwalKegiatan::class, 'jenisKegiatanID', 'jenisKegiatanID');
    }
}
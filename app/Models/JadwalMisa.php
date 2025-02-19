<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMisa extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'jadwalMisaID';
    protected $table = 'jadwal_misa'; 

    protected $fillable = [
        'kelompokID', 
        'jenisMisaID',
        'periodeID',
        'tanggal_jam_misa'
    ];

    // Many to One dengan JenisMisa
    public function jenisMisa()
    {
        return $this->belongsTo(JenisMisa::class, 'jenisMisaID', 'jenisMisaID');
    }

    // Many to One dengan Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompokID', 'kelompokID');
    }

    // Many to One dengan Periode
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periodeID', 'periodeID');
    }

    // One to One dengan KehadiranMisa
    public function kehadiranMisa()
    {
        return $this->hasOne(KehadiranMisa::class, 'jadwalMisaID', 'jadwalMisaID');
    }
}
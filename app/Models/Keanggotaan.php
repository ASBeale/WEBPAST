<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keanggotaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'keanggotaanID';
    protected $table = 'keanggotaan';

    protected $fillable = [
        'anggotaID',
        'kelompokID',
        'jabatanID',
        'periodeID',
    ];

    // Many to One dengan Periode 
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periodeID', 'periodeID');
    }

    // Optional Many to One dengan Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggotaID', 'anggotaID');
    }

    // Mandatory Many to One dengan Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompokID', 'kelompokID')
                    ->withDefault(false);
    }

    // Many to Optional One dengan Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatanID', 'jabatanID')
                    ->withDefault();
    }

    // One to Many dengan KehadiranMisaAnggota
    public function kehadiranMisaAnggota()
    {
        return $this->hasMany(KehadiranMisaAnggota::class, 'keanggotaanID', 'keanggotaanID');
    }

    // filter berdasarkan periode
    public function scopeByPeriode($query, $periodeID)
    {
        return $query->where('periodeID', $periodeID);
    }
}
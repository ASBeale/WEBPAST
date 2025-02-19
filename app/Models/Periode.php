<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $primaryKey = 'periodeID';
    protected $table = 'periode';

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai_periode',
        'tanggal_selesai_periode',
        'status_periode'
    ];

    protected $casts = [
        'tanggal_mulai_periode' => 'date',
        'tanggal_selesai_periode' => 'date',
        'status_periode' => 'boolean'
    ];

    // One to Many dengan Keanggotaan
    public function keanggotaan()
    {
        return $this->hasMany(Keanggotaan::class, 'periodeID', 'periodeID');
    }

    // One to Many dengan jadwalMisa
    public function jadwalMisa()
    {
        return $this->hasMany(JadwalMisa::class, 'periodeID', 'periodeID');
    }
}
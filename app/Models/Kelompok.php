<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $primaryKey = 'kelompokID';
    protected $table = 'kelompok';

    protected $fillable = ['nama_kelompok'];

    // One to Many dengan Keanggotaan
    public function keanggotaans()
    {
        return $this->hasMany(Keanggotaan::class, 'kelompokID', 'kelompokID');
    }

    // One to Many dengan JadwalMisa
    public function jadwalMisas()
    {
        return $this->hasMany(JadwalMisa::class, 'kelompokID', 'kelompokID');
    }
}
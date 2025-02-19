<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMisa extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'jenisMisaID';
    protected $table = 'jenis_misa';

    protected $fillable = ['jenis_misa'];

    // One to Many dengan JadwalMisa
    public function jadwalMisas()
    {
        return $this->hasMany(JadwalMisa::class, 'jenisMisaID', 'jenisMisaID');
    }
}
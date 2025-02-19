<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'jabatanID';
    protected $table = 'jabatan';
    
    protected $fillable = [
        'nama_jabatan',
    ];

    // Optional One to Many dengan Keanggotaan
    public function keanggotaans()
    {
        return $this->hasMany(Keanggotaan::class, 'jabatanID', 'jabatanID');
    }
}
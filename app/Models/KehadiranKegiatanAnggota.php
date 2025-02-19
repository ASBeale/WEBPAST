<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KehadiranKegiatanAnggota extends Pivot
{
    use HasFactory;

    protected $table = 'kehadiran_kegiatan_anggota';
    
    public $incrementing = false;
    protected $primaryKey = ['anggotaID', 'kehadiranKegiatanID'];

    protected $fillable = [
        'anggotaID',
        'kehadiranKegiatanID',
        'status_hadir'
    ];

    protected $casts = [
        'status_hadir' => 'boolean'
    ];

    // Optional Many to One dengan Anggota
    public function anggota() {
        return $this->belongsTo(Anggota::class, 'anggotaID', 'anggotaID')
                    ->withDefault(); 
    }

    // Many to One dengan KehadiranKegiatan
    public function kehadiranKegiatan() {
        return $this->belongsTo(KehadiranKegiatan::class, 'kehadiranKegiatanID', 'kehadiranKegiatanID');
    }
}
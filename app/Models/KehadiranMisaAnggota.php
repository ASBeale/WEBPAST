<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KehadiranMisaAnggota extends Pivot
{
    use HasFactory;

    protected $table = 'kehadiran_misa_anggota';

    protected $primaryKey = ['keanggotaanID', 'kehadiranMisaID'];
    public $incrementing = false;

    protected $fillable = [
        'keanggotaanID',
        'kehadiranMisaID',
        'sebagai',
        'status_kehadiran',
        'alasan_ijin'
    ];

    // Many to One dengan Keanggotaan
    public function keanggotaan()
    {
        return $this->belongsTo(Keanggotaan::class, 'keanggotaanID', 'keanggotaanID');
    }

    // Many to One dengan KehadiranMisa
    public function kehadiranMisa() {
        return $this->belongsTo(KehadiranMisa::class, 'kehadiranMisaID', 'kehadiranMisaID');
    }
}
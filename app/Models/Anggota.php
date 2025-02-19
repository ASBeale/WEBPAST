<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Anggota extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'anggotaID';
    protected $table = 'anggota'; 

    protected $fillable = [
        'roleID', 
        'username',
        'nama',
        'DoB',
        'TelpNo',
        'ortu_nama',
        'ortu_telp_no',
        'tanggal_bergabung',
        'status_anggota',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'DoB' => 'date',
        'tanggal_bergabung' => 'date',
        'status_anggota' => 'boolean'
    ];

    // One to One dengan role
    public function role() {
        return $this->belongsTo(Role::class, 'roleID', 'roleID');
    }

    // One to Optional Many dengan Keanggotaan
    public function keanggotaan() {
        return $this->hasMany(Keanggotaan::class, 'anggotaID', 'anggotaID');
    }

    // One to Optional Many dengan KehadiranKegiatanAnggota
    public function kehadiranKegiatan() {
        return $this->hasMany(KehadiranKegiatanAnggota::class, 'anggotaID', 'anggotaID');
    }
    
    public function getAuthPassword() {
        return $this->password;
    }
}
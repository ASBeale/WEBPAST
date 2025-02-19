<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'roleID';
    protected $table = 'role';

    protected $fillable = [
        'nama_role'
    ];

    // One to One dengan Anggota
    public function anggota() {
        return $this->hasOne(Anggota::class, 'roleID', 'roleID');
    }
}
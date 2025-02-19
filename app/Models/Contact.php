<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model{

    protected $primaryKey = 'contactID';
    protected $table = 'contact';

    //apa aja yang boleh diisi
    protected $fillable = ['title', 'isi', 'image'];
}
?>
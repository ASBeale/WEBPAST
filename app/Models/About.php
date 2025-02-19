<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model{

    protected $primaryKey = 'aboutID';
    protected $table = 'about';

    //apa aja yang boleh diisi
    protected $fillable = ['title', 'body', 'image'];
}
?>
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class Information extends Model {

    use HasFactory, Sluggable;
    protected $primaryKey = 'informationID';
    protected $table = 'informations';

    //apa aja yang boleh diisi
    protected $fillable = ['title', 'slug', 'body', 'image'];

    public function getRouteKeyName()
    {
        return 'slug';   
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
?>
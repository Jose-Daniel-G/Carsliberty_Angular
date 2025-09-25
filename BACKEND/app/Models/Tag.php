<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','color'];
    
    // public function getRouteKeyName() { return "slug";  }// POR ALGUNA RAZON NO QUIERE SERVIR EL METODO getRouteKeyName
    
    public function post(){ return $this->belongsToMany(Post::class);} // Muchos a Muchos Inversa
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Config extends Model
{
    use HasFactory;
    protected $fillable = ['site_name', 'address', 'phone', 'email_contact', 'logo'];
    public function image(){ return $this->morphOne(Image::class, 'imageable');}// Relacion uno a uno Polimorfica
    
    protected static function booted()
    {
        static::saved(function ($config) {
            Cache::forget('site_config'); // ✅ limpiar cache cuando se guarde
        });

        static::deleted(function ($config) {
            Cache::forget('site_config'); // ✅ limpiar cache si se borra
        });
    }
}


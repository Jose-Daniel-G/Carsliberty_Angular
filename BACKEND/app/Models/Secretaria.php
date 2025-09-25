<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Secretaria extends Model
{
    use  HasRoles, HasFactory;
    protected $guarded = ['created_at','updated_at',];

    public function user(){ return $this->belongsTo(User::class); } // Muchos a uno inversa
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicoyPlaca extends Model
{
    use HasFactory;
    protected $table = 'picoyplaca'; 
    protected $fillable = ['horario_inicio','horario_fin', 'placas_reservadas'];
}

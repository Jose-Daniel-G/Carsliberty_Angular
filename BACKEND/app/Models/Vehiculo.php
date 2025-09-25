<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = ['placa','modelo','disponible','tipo_id','profesor_id',];
    
    public function profesor()
    { return $this->belongsTo(Profesor::class, 'profesor_id');}// Muchos a uno inverso
    
    public function tipo()
    { return $this->belongsTo(TipoVehiculo::class,  'tipo_id');}// Muchos a uno inverso
}

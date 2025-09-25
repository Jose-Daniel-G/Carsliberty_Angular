<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tipos_vehiculos';

    protected $fillable = ['tipo'];
    /**
     * Un tipo de vehículo puede tener muchos vehículos.
     * Define la relación uno-a-muchos (one-to-many).
     */
    public function vehiculos(){ return $this->hasMany(Vehiculo::class, 'tipo_id'); } // Uno a Muchos
}

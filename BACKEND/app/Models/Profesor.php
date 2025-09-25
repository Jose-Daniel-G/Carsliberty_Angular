<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
    protected $table = 'profesors';  

    protected $fillable = ['nombres', 'apellidos', 'telefono', 'user_id',];

    public function agendas(){     return $this->hasMany(Agenda::class);}  // Uno a muchos
    public function user(){       return $this->belongsTo(User::class);} // Muchos a uno
    public function vehiculos(){ return $this->hasMany(Vehiculo::class);}// Uno a muchos

    public function cursos()   // Muchos a Muchos inverso
    { return $this->belongsToMany(Curso::class, 'horario_profesor_curso', 'profesor_id', 'curso_id'); }

    public function horarios() // Muchos a Muchos inverso
    { return $this->belongsToMany(Horario::class, 'horario_profesor_curso', 'profesor_id', 'horario_id'); }
    // public function historial()
    // {
    //     return $this->hasMany(Historial::class);
    // }
    // public function pagos()
    // {
    //     return $this->hasMany(Pago::class);
    // }
}

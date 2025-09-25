<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $table = 'agendas';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
    public function asistencias() // Relación con el modelo Asistencia
    {
        return $this->hasMany(Asistencia::class, 'agenda_id');
    }
}

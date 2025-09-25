<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialCurso extends Model
{
    use HasFactory;
    protected $fillable = ['cliente_id', 'curso_id', 'fecha_completado'];

    public function cliente() { return $this->belongsTo(Cliente::class); } // Uno a muchos inversa

    public function curso() {   return $this->belongsTo(Curso::class);   } // Uno a muchos inversa
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Cliente extends Model
{
    use HasRoles, HasFactory;

    protected $table = "clientes";

    protected $guard_name = 'web';
    // protected $guarded = ['created_at', 'updated_at'];
    protected $fillable = ['nombres','apellidos','cc','genero','celular','email','direccion','contacto_emergencia','observaciones','user_id',];
    
  
    public function user(){   return $this->belongsTo(User::class); }  // Cliente pertenece a un Usuario
    public function agenda(){ return $this->hasMany(Agenda::class);  }  // Cliente tiene muchos Agenda
    public function cursos()                                           // Cliente pertenece a muchos Cursos
    {   return $this->belongsToMany(Curso::class, 'cliente_curso')->withPivot('horas_realizadas');}
    
    public function asistencias(){ return $this->hasMany(Asistencia::class, 'cliente_id');  }
    
    public function cursosCompletados(){ return $this->hasMany(HistorialCurso::class); }
}

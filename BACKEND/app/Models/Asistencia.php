<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agenda as CalendarAgenda;

class Asistencia extends Model
{
    use HasFactory;
    protected $guarded = ['created_at', 'updated_at'];
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id'); 
    }
    public function agenda()
    {
        return $this->belongsTo(CalendarAgenda::class, 'agenda_id');
    }
}

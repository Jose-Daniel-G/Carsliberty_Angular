<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Profesor;
use App\Models\Horario;
use App\Models\Cliente;
use App\Models\HorarioProfesorCurso;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        /// CREACION DE HORARIOS
        Horario::create([                //1
            'dia' => 'LUNES',
            'hora_inicio' => '6:00:00',
            'hora_fin' => '19:00:00',
            'profesor_id' => '1',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '1',
            'curso_id' => '1',
            'profesor_id' => '1',
        ]);
        Horario::create([                //2
            'dia' => 'MARTES',
            'hora_inicio' => '6:00:00',
            'hora_fin' => '18:00:00',
            'profesor_id' => '2',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '2',
            'curso_id' => '1',
            'profesor_id' => '2',
        ]);
        Horario::create([               //3
            'dia' => 'MIERCOLES',
            'hora_inicio' => '6:00:00',
            'hora_fin' => '20:00:00',
            'profesor_id' => '1',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '3',
            'curso_id' => '2',
            'profesor_id' => '1',
        ]);
        Horario::create([               //4
            'dia' => 'JUEVES',
            'hora_inicio' => '6:00:00',
            'hora_fin' => '14:00:00',
            'profesor_id' => '3',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '4',
            'curso_id' => '3',
            'profesor_id' => '3',
        ]);
        Horario::create([               //5
            'dia' => 'JUEVES',
            'hora_inicio' => '6:00:00',
            'hora_fin' => '14:00:00',
            'profesor_id' => '1',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '5',
            'curso_id' => '1',
            'profesor_id' => '1',
        ]);
        Horario::create([
            'dia' => 'VIERNES',           //6
            'hora_inicio' => '6:00:00',
            'hora_fin' => '20:00:00',
            'profesor_id' => '1',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '6',
            'curso_id' => '1',
            'profesor_id' => '1',
        ]);
        Horario::create([
            'dia' => 'SABADO',              //7
            'hora_inicio' => '6:00:00',
            'hora_fin' => '20:00:00',
            'profesor_id' => '3',
        ]);
        HorarioProfesorCurso::create([
            'horario_id' => '7',
            'curso_id' => '1',
            'profesor_id' => '3',
        ]);
    }
}

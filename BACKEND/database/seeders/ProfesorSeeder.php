<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Profesor;
use App\Models\Horario;
use App\Models\Cliente;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfesorSeeder extends Seeder
{
    public function run(): void
    {
        //--------------------------------------------]
        User::create([
            'name' => 'Profesor',
            'email' => 'profesor@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('profesor');
        Profesor::create([
            'nombres' => 'Profesor',
            'apellidos' => 'Lewis',
            'telefono' => '4564564565',
            //'especialidad' => 'A1',
            'user_id' => '4',
        ]);

        User::create([
            'name' => 'TEACHER',
            'email' => 'profesor1@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('profesor');
        Profesor::create([
            'nombres' => 'TEACHER',
            'apellidos' => 'Gallardo',
            'telefono' => '432324324',
            //'especialidad' => 'motocicletas',
            'user_id' => '5',
        ]);
        User::create([
            'name' => 'Julio Profe',
            'email' => 'profesor2@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123213'),
        ])->assignRole('profesor');
        Profesor::create([
            'nombres' => 'Julio Profe',
            'apellidos' => 'Valdes',
            'telefono' => '123123213',
            //'especialidad' => 'Transporte público',
            'user_id' => '6',
        ]);
        User::create([
            'name' => 'Martin Profe',
            'email' => 'profesor3@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123213'),
        ])->assignRole('profesor');
        Profesor::create([
            'nombres' => 'Martin Profe',
            'apellidos' => 'Valdes',
            'telefono' => '123123213',
            //'especialidad' => 'Carga o camiones',
            'user_id' => '7',
        ]);
        //--------------------------------------------]
    }
}

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

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        //--------------------------------------------]
        // -------------[ Cliente ]----------------------
        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('cliente');

        $cliente = Cliente::create([
            'nombres' => 'Cliente',
            'apellidos' => 'alracona',
            'cc' => '12312753',
            'genero' => 'M',
            'celular' => '12395113',
            'direccion' => 'Cll 9 oeste',
            'contacto_emergencia' => '65495113',
            'observaciones' => 'le irrita estar cerca del povo',
            'user_id' => '8',
        ]);
        // Relacionar con los cursos (asumiendo que los cursos ya existen)
        $cursos = Curso::whereIn('id', [1, 2])->get(); // Obtener los cursos con ID 1 y 2
        $cliente->cursos()->attach($cursos); // Crear las relaciones

        User::create([
            'name' => 'Fancisco Antonio Grijalba',
            'email' => 'francisco.grijalba@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('cliente');
        $cliente = Cliente::create([
            'nombres' => 'Fancisco Antonio',
            'apellidos' => 'Grijalba',
            'cc' => '23548965',
            'genero' => 'M',
            'celular' => '987654321',
            'direccion' => 'Cll 7 oeste',
            'contacto_emergencia' => '65495113',
            'observaciones' => 'migrana',
            'user_id' => '9',
        ]);
        // Relacionar con los cursos (asumiendo que los cursos ya existen)
        $cursos = Curso::whereIn('id', [1, 3])->get(); // Obtener los cursos con ID 1 y 3
        $cliente->cursos()->attach($cursos); // Crear las relaciones
        
        User::create([
            'name' => 'ARGEMIRO ESCOBAR GUTIERRES',
            'email' => 'argemiro.escobar@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('cliente');

        $cliente = Cliente::create([
            'nombres' => 'ARGEMIRO',
            'apellidos' => 'ESCOBAR',
            'cc' => '2354765',
            'genero' => 'M',
            'celular' => '987654321',
            'direccion' => 'Cll 7 oeste',
            'contacto_emergencia' => '65495113',
            'observaciones' => 'migrana',
            'user_id' => '10',
        ]);
        $cursos = Curso::whereIn('id', [3])->get(); // Obtener los cursos con ID 3
        $cliente->cursos()->attach($cursos); // Crear las relaciones

        User::create([
            'name' => 'Gaspar',
            'email' => 'gaspar@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('cliente');

        $cliente = Cliente::create([
            'nombres' => 'Gaspar',
            'apellidos' => 'Ijaji',
            'cc' => '23547657',
            'genero' => 'M',
            'celular' => '987654321',
            'direccion' => 'Cll 7 oeste',
            'contacto_emergencia' => '65495113',
            'observaciones' => 'migrana',
            'user_id' => '11',
        ]);
        
        $cursos = Curso::whereIn('id', [2])->get(); // Obtener los cursos con ID 1 y 2
        $cliente->cursos()->attach($cursos); // Crear las relaciones

        User::create([
            'name' => 'Espectador',
            'email' => 'espectador@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('espectador');
        //    //-------------[ USUARIOS ]----------------]
        //         User::create([
        //             'name' => 'Juan David Grijalba Osorio',
        //             'email' => 'juandavidgo1997@email.com',
        //             'email_verified_at' => now(),
        //             'password' => bcrypt('123123123'),
        //         ])->assignRole('usuario');

        //         User::factory()->create([
        //             'name' => 'Test User',
        //             'email' => 'test@email.com',
        //             'password' => bcrypt('123123123'),
        //         ])->assignRole('usuario');

        //         User::factory()->create([
        //             'name' => 'user',
        //             'email' => 'user@email.com',
        //             'password' => bcrypt('123123123'),
        //         ])->assignRole('usuario');

        //         Curso::create([
        //             'nombre' => 'Curso B1',
        //             'descripcion' => 'Curso de conducción para obtener licencia tipo B1.',
        //             'horas_requeridas' => '11',
        //             'estado' => 'A',
        //         ]);

        //         User::factory(9)->create();
    }
}

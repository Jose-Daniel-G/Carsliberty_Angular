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

class SecretariaSeeder extends Seeder
{
    public function run(): void
    {
         //----------[  SECRETARIA  ]-------------
         User::create([
            'name' => 'Secretaria',
            'email' => 'secretaria@email.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
        ])->assignRole('secretaria');

        Secretaria::create([
            'nombres' => 'Secretaria',
            'apellidos' => 'Catrana',
            'cc' => '1112036545',
            'celular' => '3147078256',
            'fecha_nacimiento' => '22/10/2010',
            'direccion' => 'calle 5 o este',
            'user_id' => '3',
        ]);
        // -------------------------------------------------
    }
}

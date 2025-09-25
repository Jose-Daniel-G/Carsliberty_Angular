<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PicoyPlaca;
use Illuminate\Support\Str;

class PicoyPlacaSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $placas_reservadas = ['7 y 8', '9 y 0', '1 y 2', '3 y 4', '5 y 6'];

        foreach ($categories as $index => $day) {
            PicoyPlaca::create([
                'dia' => $day,
                'horario_inicio' => '07:00:00', // Hora de inicio de la restricción
                'horario_fin' => '20:00:00',    // Hora de fin de la restricción
                'placas_reservadas' => $placas_reservadas[$index], // Placas afectadas
            ]);
        }
    }
}

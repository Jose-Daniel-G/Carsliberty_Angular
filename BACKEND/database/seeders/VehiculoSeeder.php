<?php

namespace Database\Seeders;

use App\Models\PicoyPlaca;
use App\Models\Vehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
          // Crear vehículos con terminaciones específicas en las placas
          Vehiculo::factory()->count(10)->create();
    }
}

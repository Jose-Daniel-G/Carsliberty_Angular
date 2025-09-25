<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TipoVehiculo;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_vehiculos')->insert([
            ['tipo' => 'sedan'],
            ['tipo' => 'suv'],
            ['tipo' => 'pickup'],
            ['tipo' => 'hatchback'],
        ]);
        // $tipos = ['sedan', 'suv', 'pickup', 'hatchback'];

        // foreach ($tipos as $tipo) {
        //     DB::table('tipos_vehiculos')->insert(['tipo' => $tipo]);
        // }

    }
}

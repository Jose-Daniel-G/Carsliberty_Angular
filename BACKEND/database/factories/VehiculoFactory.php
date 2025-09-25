<?php

namespace Database\Factories;

use App\Models\PicoyPlaca;
use App\Models\Profesor;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehiculo>
 */
class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    public function definition()
    {
        return [
            'placa' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{3}'), // Ejemplo: ABC-123
            'modelo' => $this->faker->word(),
            'disponible' => $this->faker->boolean(),
            'tipo_id' => $this->faker->randomElement(['1', '2', '3', '4']),
            'profesor_id' => $this->faker->randomElement(['1', '2', '3', '4']), // Asume que tienes un factory para User
        ];
    }
}

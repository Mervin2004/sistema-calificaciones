<?php

namespace Database\Factories;

use App\Models\Asignatura;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'cedula' => (string) $this->faker->unique()->randomNumber(8, true), // Casteado a string
            'asignatura_id' => Asignatura::inRandomOrder()->first()->id ?? Asignatura::factory(),
        ];
    }
}
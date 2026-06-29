<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'cedula' => $this->faker->unique()->randomNumber(8, true), // Genera un número de 8 dígitos
            'nacimiento' => $this->faker->date(),
            'edad' => $this->faker->numberBetween(10, 18),
        ];
    }
}
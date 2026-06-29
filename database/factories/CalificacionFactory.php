<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Asignatura;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalificacionFactory extends Factory
{
    public function definition()
    {
        return [
            'alumno_id' => Alumno::inRandomOrder()->first()->id ?? Alumno::factory(),
            'asignatura_id' => Asignatura::inRandomOrder()->first()->id ?? Asignatura::factory(),
            'calificacion' => $this->faker->randomFloat(2, 0, 20),
        ];
    }
}
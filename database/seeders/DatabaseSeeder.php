<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Calificacion;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // El orden es importante por las relaciones de llaves foráneas
        Asignatura::factory(5)->create();
        Alumno::factory(10)->create();
        Profesor::factory(5)->create();
        Calificacion::factory(20)->create();
    }
}
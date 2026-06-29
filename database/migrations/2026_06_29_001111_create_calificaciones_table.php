<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('calificaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
        $table->foreignId('asignatura_id')->constrained('asignaturas')->onDelete('cascade');
        $table->decimal('calificacion', 5, 2); // Permite decimales, ej: 18.50 o 20.00
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificacions');
    }
};

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
    Schema::create('alumnos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('apellido');
        $table->integer('cedula')->unique();
        $table->date('nacimiento');
        $table->integer('edad');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};

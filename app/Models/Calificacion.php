<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    // Agrega esta línea:
    protected $table = 'calificaciones';

    protected $fillable = ['alumno_id', 'asignatura_id', 'calificacion'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
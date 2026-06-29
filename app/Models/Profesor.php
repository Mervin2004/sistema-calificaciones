<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
    
    // Agrega esta línea:
    protected $table = 'profesores';

    protected $fillable = ['nombre', 'apellido', 'cedula', 'asignatura_id'];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
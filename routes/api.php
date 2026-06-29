<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\CalificacionController;

Route::apiResource('alumnos', AlumnoController::class);
Route::apiResource('asignaturas', AsignaturaController::class);
Route::apiResource('profesores', ProfesorController::class);
Route::apiResource('calificaciones', CalificacionController::class);
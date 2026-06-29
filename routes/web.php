<?php

use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas para las vistas del frontend
Route::get('/alumnos', function () {
    return view('alumnos');
});

Route::get('/asignaturas', function () {
    return view('asignaturas');
});

Route::get('/profesores', function () {
    return view('profesores');
});

Route::get('/calificaciones', function () {
    return view('calificaciones');
});
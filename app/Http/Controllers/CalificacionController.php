<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class CalificacionController extends Controller
{
    public function index()
    {
        try {
            // Carga alumno y asignatura 
            $calificaciones = Calificacion::with(['alumno', 'asignatura'])->get();
            return response()->json($calificaciones, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener calificaciones'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alumno_id' => 'required|exists:alumnos,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'calificacion' => 'required|numeric|min:0|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        try {
            $calificacion = Calificacion::create($request->all());
            return response()->json($calificacion, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear'], 500);
        }
    }

    public function show($id)
    {
        try {
            $calificacion = Calificacion::with(['alumno', 'asignatura'])->findOrFail($id);
            return response()->json($calificacion, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Calificación no encontrada'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $calificacion = Calificacion::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'calificacion' => 'numeric|min:0|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json(['errores' => $validator->errors()], 422);
            }

            $calificacion->update($request->all());
            return response()->json($calificacion, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $calificacion = Calificacion::findOrFail($id);
            $calificacion->delete();
            return response()->json(['mensaje' => 'Calificación eliminada correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar'], 500);
        }
    }
}
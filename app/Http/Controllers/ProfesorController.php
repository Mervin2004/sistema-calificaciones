<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProfesorController extends Controller
{
    public function index()
    {
        try {
            // Carga al profesor con su asignatura [cite: 56]
            $profesores = Profesor::with('asignatura')->get();
            return response()->json($profesores, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los profesores', 'detalle' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|unique:profesores,cedula',
            'asignatura_id' => 'required|exists:asignaturas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422); // 
        }

        try {
            $profesor = Profesor::create($request->all());
            return response()->json($profesor, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear el profesor'], 500); // 
        }
    }

    public function show($id)
    {
        try {
            $profesor = Profesor::with('asignatura')->findOrFail($id);
            return response()->json($profesor, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Profesor no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $profesor = Profesor::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'string|max:255',
                'apellido' => 'string|max:255',
                'cedula' => 'string|unique:profesores,cedula,'.$profesor->id,
                'asignatura_id' => 'exists:asignaturas,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errores' => $validator->errors()], 422);
            }

            $profesor->update($request->all());
            return response()->json($profesor, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $profesor = Profesor::findOrFail($id);
            $profesor->delete();
            return response()->json(['mensaje' => 'Profesor eliminado correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar'], 500);
        }
    }
}
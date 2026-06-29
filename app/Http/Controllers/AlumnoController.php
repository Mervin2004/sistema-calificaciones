<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AlumnoController extends Controller
{
    public function index()
    {
        try {
            // Únicamente se requiere la información del alumno
            $alumnos = Alumno::all();
            return response()->json($alumnos, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los alumnos', 'detalle' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|integer|unique:alumnos,cedula',
            'nacimiento' => 'required|date',
            'edad' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        try {
            $alumno = Alumno::create($request->all());
            return response()->json($alumno, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al registrar al alumno'], 500);
        }
    }

    public function show($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            return response()->json($alumno, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $alumno = Alumno::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'string|max:255',
                'apellido' => 'string|max:255',
                'cedula' => 'integer|unique:alumnos,cedula,' . $alumno->id,
                'nacimiento' => 'date',
                'edad' => 'integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errores' => $validator->errors()], 422);
            }

            $alumno->update($request->all());
            return response()->json($alumno, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el alumno'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            $alumno->delete();
            return response()->json(['mensaje' => 'Alumno eliminado correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el alumno'], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AsignaturaController extends Controller
{
    public function index()
    {
        try {
            // Únicamente se requiere la información de las asignaturas
            $asignaturas = Asignatura::all();
            return response()->json($asignaturas, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las asignaturas', 'detalle' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        try {
            $asignatura = Asignatura::create($request->all());
            return response()->json($asignatura, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la asignatura'], 500);
        }
    }

    public function show($id)
    {
        try {
            $asignatura = Asignatura::findOrFail($id);
            return response()->json($asignatura, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Asignatura no encontrada'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $asignatura = Asignatura::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'string|max:255',
                'descripcion' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errores' => $validator->errors()], 422);
            }

            $asignatura->update($request->all());
            return response()->json($asignatura, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la asignatura'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $asignatura = Asignatura::findOrFail($id);
            $asignatura->delete();
            return response()->json(['mensaje' => 'Asignatura eliminada correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar la asignatura'], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Proyecto;

class cDepartamento extends Controller
{
    // Listar todos los departamentos de un proyecto
    public function index($proyectoId)
    {
        $departamentos = Departamento::where('project_id', $proyectoId)->get();
        return response()->json($departamentos);
    }

    // Mostrar un departamento específico
    public function show($id)
    {
        $departamento = Departamento::findOrFail($id);
        return response()->json($departamento);
    }

    // Crear un departamento
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:proyectos,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $departamento = Departamento::create($data);

        return response()->json($departamento, 201);
    }

    // Actualizar un departamento
    public function update(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $departamento->update($data);

        return response()->json($departamento);
    }

    // Eliminar un departamento
    public function destroy($id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return response()->json(['message' => 'Departamento eliminado']);
    }
}

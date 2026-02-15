<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Departamento;

class cTarea extends Controller
{
    // Listar tareas de un departamento
    public function index($departmentId)
    {
        $tareas = Tarea::where('department_id', $departmentId)->get();
        return response()->json($tareas);
    }

    // Mostrar una tarea
    public function show($id)
    {
        $tarea = Tarea::findOrFail($id);
        return response()->json($tarea);
    }

    // Crear una tarea
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estado' => 'required|in:pendiente,wip,finalizado',
            'department_id' => 'required|exists:departamentos,id',
        ]);

        $tarea = Tarea::create($data);

        return response()->json($tarea, 201);
    }

    // Actualizar una tarea
    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estado' => 'required|in:pendiente,wip,finalizado',
        ]);

        $tarea->update($data);

        return response()->json($tarea);
    }

    // Eliminar una tarea
    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada']);
    }
}

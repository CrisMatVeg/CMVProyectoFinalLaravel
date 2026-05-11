<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;

class cTarea extends Controller
{
    public function index($projectId)
    {
        $tareas = Tarea::where('project_id', $projectId)
            ->with(['tipo', 'status', 'usuarios', 'dependencias'])
            ->get();
        return response()->json($tareas);
    }

    public function show($id)
    {
        $tarea = Tarea::with(['tipo', 'status', 'usuarios', 'dependencias'])->findOrFail($id);
        return response()->json($tarea);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'project_id'      => 'required|exists:proyectos,id',
            'type_id'         => 'required|exists:tipos,id',
            'status_id'       => 'required|exists:estados,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'is_milestone'    => 'nullable|boolean',
            'depends_on'      => 'nullable|array',
            'depends_on.*'    => 'exists:tareas,id',
        ]);

        $data['is_milestone'] = $request->boolean('is_milestone');

        $tarea = Tarea::create($data);

        $depIds = $request->input('depends_on', []);
        if (!empty($depIds)) {
            $tarea->dependencias()->sync($depIds);
        }

        return redirect()->back()->with('success', 'Tarea creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'type_id'         => 'required|exists:tipos,id',
            'status_id'       => 'required|exists:estados,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'is_milestone'    => 'nullable|boolean',
            'depends_on'      => 'nullable|array',
            'depends_on.*'    => 'exists:tareas,id',
        ]);

        $data['is_milestone'] = $request->boolean('is_milestone');

        $tarea->update($data);

        // Sync siempre (vacío = sin dependencias)
        $tarea->dependencias()->sync($request->input('depends_on', []));

        return redirect()->back()->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy($id)
    {
        Tarea::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Tarea eliminada correctamente.');
    }
}

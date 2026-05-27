<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;

/**
 * Gestiona el ciclo de vida de las tareas de un proyecto:
 * creación, edición, eliminación y listado vía API interna.
 */
class cTarea extends Controller
{
    /**
     * Devuelve en JSON todas las tareas de un proyecto.
     * Usado por el frontend para cargar datos sin recargar la página.
     *
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($projectId)
    {
        $tareas = Tarea::where('project_id', $projectId)
            ->with(['tipo', 'status', 'usuarios', 'dependencias'])
            ->get();

        return response()->json($tareas);
    }

    /**
     * Devuelve en JSON una tarea con sus relaciones cargadas.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $tarea = Tarea::with(['tipo', 'status', 'usuarios', 'dependencias'])->findOrFail($id);

        return response()->json($tarea);
    }

    /**
     * Crea una nueva tarea y sincroniza sus dependencias si se proporcionan.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        $tarea  = Tarea::create($data);
        $depIds = $request->input('depends_on', []);

        if (!empty($depIds)) {
            $tarea->dependencias()->sync($depIds);
        }

        return redirect()->back()->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Actualiza los datos de una tarea existente y re-sincroniza sus dependencias.
     *
     * @param Request $request
     * @param int     $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

        // Sync siempre: array vacío = sin dependencias
        $tarea->dependencias()->sync($request->input('depends_on', []));

        return redirect()->back()->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Elimina una tarea permanentemente.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Tarea::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Tarea eliminada correctamente.');
    }
}

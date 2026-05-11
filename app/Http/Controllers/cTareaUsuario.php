<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Usuario;
use App\Models\Proyecto;

class cTareaUsuario extends Controller
{
    // Asignar un usuario a una tarea
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'task_id' => 'required|exists:tareas,id',
        ]);

        $tarea = Tarea::findOrFail($request->task_id);
        $tarea->usuarios()->syncWithoutDetaching($request->user_id);

        // Auto-añadir al usuario como miembro del proyecto si aún no lo es
        $proyecto = $tarea->proyecto;
        $proyecto->miembros()->syncWithoutDetaching($request->user_id);

        return redirect()->back()->with('success', 'Usuario asignado correctamente.');
    }

    // Asignar todos los usuarios de un departamento (tipo) a una tarea
    public function assignDepartamento(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tareas,id',
            'tipo_id' => 'required|exists:tipos,id',
        ]);

        $tarea    = Tarea::findOrFail($request->task_id);
        $proyecto = $tarea->proyecto;

        $userIds = \App\Models\ProyectoAcceso::where('proyecto_id', $proyecto->id)
            ->where('tipo_id', $request->tipo_id)
            ->pluck('user_id')
            ->toArray();

        foreach ($userIds as $userId) {
            $tarea->usuarios()->syncWithoutDetaching($userId);
            $proyecto->miembros()->syncWithoutDetaching($userId);
        }

        return redirect()->back()->with('success', 'Departamento asignado correctamente.');
    }

    // Quitar un usuario de una tarea
    public function remove(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'task_id' => 'required|exists:tareas,id',
        ]);

        $tarea = Tarea::findOrFail($request->task_id);
        $tarea->usuarios()->detach($request->user_id);

        return redirect()->back()->with('success', 'Usuario removido correctamente.');
    }
}

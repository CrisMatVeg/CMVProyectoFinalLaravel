<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarea;

/**
 * Gestiona la asignación y desasignación de usuarios a tareas de un proyecto.
 * Solo el propietario del proyecto puede realizar estas operaciones.
 */
class cTareaUsuario extends Controller
{
    /**
     * Asigna un usuario a una tarea.
     * Verifica que el usuario pertenezca al área (tipo) de la tarea antes de asignarlo.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'task_id' => 'required|exists:tareas,id',
        ]);

        $tarea    = Tarea::findOrFail($request->task_id);
        $userId   = $request->user_id;
        $proyecto = $tarea->proyecto;

        // Solo el owner del proyecto puede asignar usuarios a tareas
        if (Auth::id() !== $proyecto->created_by) {
            return redirect()->back()->with('error', 'Solo el propietario del proyecto puede asignar usuarios a tareas.');
        }

        // El owner no puede ser invitado a tareas
        if ($userId == $proyecto->created_by) {
            return redirect()->back()->with('error', 'El owner del proyecto no puede ser asignado a tareas.');
        }

        // Si hay registros de acceso por tipo, exigir que el usuario pertenezca al área de la tarea
        $hayRegistros = \App\Models\ProyectoAcceso::where('proyecto_id', $proyecto->id)->exists();
        if ($hayRegistros) {
            $tieneAcceso = \App\Models\ProyectoAcceso::where('proyecto_id', $proyecto->id)
                ->where('user_id', $userId)
                ->where('tipo_id', $tarea->type_id)
                ->exists();

            if (!$tieneAcceso) {
                return redirect()->back()->with('error', 'El usuario no pertenece al departamento de esta tarea.');
            }
        }

        $tarea->usuarios()->syncWithoutDetaching($userId);

        return redirect()->back()->with('success', 'Usuario asignado correctamente.');
    }

    /**
     * Asigna todos los usuarios de un área (tipo) a una tarea de forma masiva.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            ->where('user_id', '!=', $proyecto->created_by)
            ->pluck('user_id')
            ->toArray();

        foreach ($userIds as $userId) {
            $tarea->usuarios()->syncWithoutDetaching($userId);
        }

        return redirect()->back()->with('success', 'Departamento asignado correctamente.');
    }

    /**
     * Elimina la asignación de un usuario a una tarea.
     * Solo el propietario del proyecto puede realizar esta operación.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'task_id' => 'required|exists:tareas,id',
        ]);

        $tarea    = Tarea::findOrFail($request->task_id);
        $proyecto = $tarea->proyecto;

        // Solo el owner del proyecto puede remover usuarios de tareas
        if (Auth::id() !== $proyecto->created_by) {
            return redirect()->back()->with('error', 'Solo el propietario del proyecto puede remover usuarios de tareas.');
        }

        $tarea->usuarios()->detach($request->user_id);

        return redirect()->back()->with('success', 'Usuario removido correctamente.');
    }
}

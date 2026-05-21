<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;

class cTareaUsuario extends Controller
{
    // Asignar un usuario a una tarea
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'task_id' => 'required|exists:tareas,id',
        ]);

        $tarea   = Tarea::findOrFail($request->task_id);
        $userId  = $request->user_id;
        $proyecto = $tarea->proyecto;

        // Solo el owner del proyecto puede asignar usuarios a tareas
        if (auth()->id() !== $proyecto->created_by) {
            return redirect()->back()->with('error', 'Solo el propietario del proyecto puede asignar usuarios a tareas.');
        }

        // Verificar que el usuario pertenece al departamento de la tarea
        $tieneAcceso = \App\Models\ProyectoAcceso::where('proyecto_id', $proyecto->id)
            ->where('user_id', $userId)
            ->where('tipo_id', $tarea->type_id)
            ->exists();

        // El owner no puede ser invitado a tareas
        if ($userId == $proyecto->created_by) {
            return redirect()->back()->with('error', 'El owner del proyecto no puede ser asignado a tareas.');
        }

        // Si hay registros de acceso por tipo, exigir que el usuario pertenezca al departamento
        $hayRegistros = \App\Models\ProyectoAcceso::where('proyecto_id', $proyecto->id)->exists();
        if ($hayRegistros && !$tieneAcceso) {
            return redirect()->back()->with('error', 'El usuario no pertenece al departamento de esta tarea.');
        }

        $tarea->usuarios()->syncWithoutDetaching($userId);

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
            ->where('user_id', '!=', $proyecto->created_by)
            ->pluck('user_id')
            ->toArray();

        foreach ($userIds as $userId) {
            $tarea->usuarios()->syncWithoutDetaching($userId);
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

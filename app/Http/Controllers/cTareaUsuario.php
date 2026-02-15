<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Usuario;

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

        return response()->json([
            'message' => 'Usuario asignado a la tarea correctamente.'
        ]);
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

        return response()->json([
            'message' => 'Usuario removido de la tarea correctamente.'
        ]);
    }
}

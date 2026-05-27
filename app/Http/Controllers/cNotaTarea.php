<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\NotaTarea;
use Illuminate\Support\Facades\Auth;

/**
 * Gestiona las notas asociadas a las tareas de un proyecto.
 */
class cNotaTarea extends Controller
{
    /**
     * Añade una nueva nota a una tarea.
     * Solo pueden hacerlo el owner del proyecto y los usuarios asignados a esa tarea.
     *
     * @param Request $request
     * @param int     $tareaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $tareaId)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $tarea   = Tarea::findOrFail($tareaId);
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $esOwner  = $tarea->proyecto->created_by === $usuario->id;
        $asignado = $tarea->usuarios->contains('id', $usuario->id);

        if (!$esOwner && !$asignado) {
            return redirect()->back()->with('error', 'No tienes permiso para añadir notas a esta tarea.');
        }

        NotaTarea::create([
            'task_id'   => $tarea->id,
            'user_id'   => $usuario->id,
            'contenido' => $request->contenido,
        ]);

        return redirect()->back()->with('success', 'Nota añadida correctamente.');
    }

    /**
     * Elimina una nota.
     * Solo pueden hacerlo el owner del proyecto o el autor de la nota.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $nota    = NotaTarea::findOrFail($id);
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $esOwner = $nota->tarea->proyecto->created_by === $usuario->id;
        $esAutor = $nota->user_id === $usuario->id;

        if (!$esOwner && !$esAutor) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta nota.');
        }

        $nota->delete();

        return redirect()->back()->with('success', 'Nota eliminada.');
    }
}

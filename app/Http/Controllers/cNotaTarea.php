<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\NotaTarea;
use Illuminate\Support\Facades\Auth;

class cNotaTarea extends Controller
{
    public function store(Request $request, $tareaId)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $tarea   = Tarea::findOrFail($tareaId);
        $usuario = Auth::user();

        // Solo usuarios asignados o el owner del proyecto pueden añadir notas
        $esOwner   = $tarea->proyecto->created_by === $usuario->id;
        $asignado  = $tarea->usuarios->contains('id', $usuario->id);

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

    public function destroy($id)
    {
        $nota    = NotaTarea::findOrFail($id);
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

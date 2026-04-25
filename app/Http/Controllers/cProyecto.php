<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\ProyectoAcceso;
use App\Models\Tipo;
use Illuminate\Support\Facades\Auth;

class cProyecto extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $proyecto = Proyecto::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'created_by' => Auth::id(),
        ]);

        $proyecto->miembros()->attach(Auth::id());

        // Owner recibe acceso a todas las áreas del proyecto
        foreach (Tipo::pluck('id') as $tipoId) {
            ProyectoAcceso::create([
                'proyecto_id' => $proyecto->id,
                'user_id'     => Auth::id(),
                'tipo_id'     => $tipoId,
            ]);
        }

        return redirect()->route('proyectos');
    }

    // Actualizar proyecto
    public function update(Request $request, Proyecto $proyecto)
    {
        // Solo el creador puede modificar
        if ($proyecto->created_by !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar este proyecto.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $proyecto->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('proyectos')->with('success', 'Proyecto actualizado correctamente.');
    }

    // Eliminar proyecto
    public function destroy(Proyecto $proyecto)
    {
        // Solo el creador puede eliminar
        if ($proyecto->created_by !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este proyecto.');
        }

        $proyecto->delete(); // Esto borrará también los departamentos por la relación cascade

        return redirect()->route('proyectos')->with('success', 'Proyecto eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

/**
 * Gestiona la creación, actualización y eliminación de proyectos.
 */
class cProyecto extends Controller
{
    /**
     * Crea un nuevo proyecto para el usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Proyecto::create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('proyectos');
    }

    /**
     * Actualiza los datos de un proyecto.
     * Solo el creador puede modificarlo.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        if ($proyecto->created_by !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar este proyecto.');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $proyecto->update([
            'name'        => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('proyectos')->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * Elimina un proyecto y todos sus datos asociados (cascade).
     * Solo el creador puede eliminarlo.
     *
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Proyecto $proyecto)
    {
        if ($proyecto->created_by !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este proyecto.');
        }

        $proyecto->delete();

        return redirect()->route('proyectos')->with('success', 'Proyecto eliminado correctamente.');
    }
}

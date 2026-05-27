<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Verifica que el usuario autenticado sea miembro del proyecto.
     * Lanza 403 si no lo es.
     *
     * @param Proyecto $proyecto
     */
    protected function verificarMiembro(Proyecto $proyecto): void
    {
        $esMiembro = $proyecto->miembros()->where('id', Auth::id())->exists();

        if (!$esMiembro) {
            abort(403, 'No eres miembro de este proyecto.');
        }
    }
}

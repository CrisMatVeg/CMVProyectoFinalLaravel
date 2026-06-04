<?php

namespace App\Http\Middleware;

use App\Models\Proyecto;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarMiembroProyecto
{
    public function handle(Request $request, Closure $next)
    {
        $proyectoId = (int) $request->route('proyecto');

        if (!$proyectoId) {
            return $next($request);
        }

        $proyecto = Proyecto::findOrFail($proyectoId);
        $esMiembro = $proyecto->miembros()->where('id', Auth::id())->exists();

        if (!$esMiembro) {
            abort(403, 'No eres miembro de este proyecto.');
        }

        return $next($request);
    }
}

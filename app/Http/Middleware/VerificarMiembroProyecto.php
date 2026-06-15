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
        $proyecto = $request->route('proyecto');

if (!($proyecto instanceof Proyecto)) {
    return $next($request);
}

$esMiembro = $proyecto->miembros()->where('id', Auth::id())->exists();

        if (!$esMiembro) {
            abort(403, 'No eres miembro de este proyecto.');
        }

        return $next($request);
    }
}

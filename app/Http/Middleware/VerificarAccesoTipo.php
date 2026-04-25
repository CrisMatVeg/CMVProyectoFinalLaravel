<?php

namespace App\Http\Middleware;

use App\Models\ProyectoAcceso;
use App\Models\Proyecto;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarAccesoTipo
{
    public function handle(Request $request, Closure $next)
    {
        $usuario    = Auth::user();
        $proyectoId = (int) $request->route('proyectoId') ?: (int) $request->route('proyecto');
        $tipoId     = (int) $request->route('tipoId')    ?: (int) $request->route('tipo');

        $proyecto = Proyecto::findOrFail($proyectoId);

        // Owner: acceso total
        if ($proyecto->created_by === $usuario->id) {
            return $next($request);
        }

        // Sin rows en proyecto_accesos: acceso legacy completo
        $tieneAccesos = ProyectoAcceso::where('proyecto_id', $proyectoId)
            ->where('user_id', $usuario->id)
            ->exists();

        if (!$tieneAccesos) {
            return $next($request);
        }

        // Con rows: verificar acceso al tipo específico
        $tieneEsteAcceso = ProyectoAcceso::where('proyecto_id', $proyectoId)
            ->where('user_id', $usuario->id)
            ->where('tipo_id', $tipoId)
            ->exists();

        if (!$tieneEsteAcceso) {
            abort(403, 'Sin acceso a esta área.');
        }

        return $next($request);
    }
}

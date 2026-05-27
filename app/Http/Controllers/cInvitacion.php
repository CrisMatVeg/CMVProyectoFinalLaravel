<?php

namespace App\Http\Controllers;

use App\Models\Invitacion;
use App\Models\Proyecto;
use App\Models\ProyectoAcceso;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Gestiona el flujo completo de invitaciones a proyectos:
 * generación de códigos, unión pública por enlace y unión de usuarios autenticados.
 */
class cInvitacion extends Controller
{
    /**
     * Genera una nueva invitación con código único para el proyecto dado.
     * Solo el owner del proyecto puede generar invitaciones.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generar(Request $request, Proyecto $proyecto)
    {
        if ($proyecto->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'areas'   => 'required|array|min:1',
            'areas.*' => 'integer|exists:tipos,id',
        ]);

        $invitacion = Invitacion::create([
            'proyecto_id' => $proyecto->id,
            'codigo'      => Invitacion::generarCodigo(),
            'created_by'  => Auth::id(),
            'expires_at'  => now()->addDays(7),
            'uses_count'  => 0,
            'areas'       => $request->areas,
        ]);

        $url = url('/join?code=' . $invitacion->codigo);

        return back()->with('invite_url', $url)->with('invite_code', $invitacion->codigo);
    }

    /**
     * Procesa el enlace de invitación público (/join?code=XXX).
     * Si el usuario está autenticado, lo une al proyecto directamente.
     * Si no está autenticado, guarda el código en sesión y redirige al registro.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(Request $request)
    {
        $codigo     = trim(strtoupper($request->query('code', '')));
        $invitacion = Invitacion::with('proyecto')->where('codigo', $codigo)->first();

        if (!$invitacion || !$invitacion->esValida()) {
            return redirect()->route('home')
                ->withErrors(['invite' => 'El enlace de invitación no es válido o ha expirado.']);
        }

        if (Auth::check()) {
            /** @var Usuario $usuario */
            $usuario = Auth::user();
            return $this->unirAlProyecto($usuario, $invitacion);
        }

        $request->session()->put('invite_code', $codigo);

        return redirect()->route('registro', ['code' => $codigo]);
    }

    /**
     * Une al usuario autenticado a un proyecto usando un código de invitación
     * enviado manualmente desde el formulario.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unirse(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $invitacion = Invitacion::with('proyecto')
            ->where('codigo', trim(strtoupper($request->codigo)))
            ->first();

        if (!$invitacion || !$invitacion->esValida()) {
            return back()->withErrors(['codigo' => 'Código inválido o expirado.']);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        return $this->unirAlProyecto($usuario, $invitacion);
    }

    /**
     * Lógica compartida: une a un usuario a un proyecto usando una invitación.
     * Incrementa el contador de usos y crea los accesos por área.
     * Operación atómica envuelta en transacción DB.
     *
     * @param Usuario    $usuario
     * @param Invitacion $invitacion
     * @return \Illuminate\Http\RedirectResponse
     */
    private function unirAlProyecto(Usuario $usuario, Invitacion $invitacion)
    {
        $proyecto  = $invitacion->proyecto;
        $yaMiembro = $proyecto->miembros()->where('id', $usuario->id)->exists();

        DB::transaction(function () use ($usuario, $invitacion, $proyecto, $yaMiembro) {
            if (!$yaMiembro) {
                $invitacion->increment('uses_count');
            }

            if (!empty($invitacion->areas)) {
                foreach ($invitacion->areas as $tipoId) {
                    ProyectoAcceso::firstOrCreate([
                        'proyecto_id' => $proyecto->id,
                        'user_id'     => $usuario->id,
                        'tipo_id'     => $tipoId,
                    ]);
                }
            }
        });

        return redirect()->route('proyecto', $proyecto->id)
            ->with('success', $yaMiembro
                ? 'Ya eres miembro de este proyecto.'
                : '¡Te uniste al proyecto ' . $proyecto->name . '!');
    }
}

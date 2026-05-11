<?php

namespace App\Http\Controllers;

use App\Models\Invitacion;
use App\Models\Proyecto;
use App\Models\ProyectoAcceso;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class cInvitacion extends Controller
{
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
            'max_uses'    => null,
            'uses_count'  => 0,
            'areas'       => $request->areas,
        ]);

        $url = url('/join?code=' . $invitacion->codigo);

        return back()->with('invite_url', $url)->with('invite_code', $invitacion->codigo);
    }

    public function join(Request $request)
    {
        $codigo = trim(strtoupper($request->query('code', '')));

        $invitacion = Invitacion::with('proyecto')->where('codigo', $codigo)->first();

        if (!$invitacion || !$invitacion->esValida()) {
            return redirect()->route('home')
                ->withErrors(['invite' => 'El enlace de invitación no es válido o ha expirado.']);
        }

        if (Auth::check()) {
            return $this->unirAlProyecto(Usuario::findOrFail(Auth::id()), $invitacion);
        }

        $request->session()->put('invite_code', $codigo);

        return redirect()->route('registro', ['code' => $codigo]);
    }

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

        return $this->unirAlProyecto(Usuario::findOrFail(Auth::id()), $invitacion);
    }

    private function unirAlProyecto(Usuario $usuario, Invitacion $invitacion)
    {
        $proyecto = $invitacion->proyecto;

        $yaMiembro = $proyecto->miembros()->where('user_id', $usuario->id)->exists();

        if (!$yaMiembro) {
            $proyecto->miembros()->attach($usuario->id);
            $invitacion->increment('uses_count');
        }

        // Crear accesos por área si la invitación los especifica
        if (!empty($invitacion->areas)) {
            foreach ($invitacion->areas as $tipoId) {
                ProyectoAcceso::firstOrCreate([
                    'proyecto_id' => $proyecto->id,
                    'user_id'     => $usuario->id,
                    'tipo_id'     => $tipoId,
                ]);
            }
        }

        return redirect()->route('proyecto', $proyecto->id)
            ->with('success', $yaMiembro
                ? 'Ya eres miembro de este proyecto.'
                : '¡Te uniste al proyecto ' . $proyecto->name . '!');
    }
}

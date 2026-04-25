<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class cUsuario extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegistro(Request $request)
    {
        $invitacion = null;
        if ($request->has('code')) {
            $invitacion = \App\Models\Invitacion::with('proyecto')
                ->where('codigo', $request->code)
                ->vigente()
                ->first();
            if ($invitacion) {
                $request->session()->put('invite_code', $request->code);
            }
        }
        return view('registro', compact('invitacion'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = Usuario::where('username', $request->username)->first();
        $passwordConcatenada = $request->username . $request->password;

        if ($user && Hash::check($passwordConcatenada, $user->password)) {
            /* dd($user, get_class($user)); */
            Auth::login($user); // inicia sesión
            return redirect()->route('proyectos');
        }

        return back()->withErrors(['username' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function registro(Request $request)
    {
        // Validación
        $request->validate([
            'username' => 'required|unique:usuarios,username',
            'description' => 'required',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:4',
        ]);

        $passwordConcatenada= $request->username . $request->password;

        // Crear usuario
        $usuario = new Usuario();
        $usuario->username = $request->username;
        $usuario->description = $request->description;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($passwordConcatenada);
        $usuario->save();

        // Iniciar sesión automáticamente
        Auth::login($usuario);

        $codigoPendiente = $request->session()->pull('invite_code')
                        ?? $request->input('invite_code');

        if ($codigoPendiente) {
            $inv = \App\Models\Invitacion::with('proyecto')
                ->where('codigo', $codigoPendiente)->first();
            if ($inv && $inv->esValida()) {
                $inv->proyecto->miembros()->attach($usuario->id);
                $inv->increment('uses_count');

                if (!empty($inv->areas)) {
                    foreach ($inv->areas as $tipoId) {
                        \App\Models\ProyectoAcceso::firstOrCreate([
                            'proyecto_id' => $inv->proyecto->id,
                            'user_id'     => $usuario->id,
                            'tipo_id'     => $tipoId,
                        ]);
                    }
                }

                return redirect()->route('proyecto', $inv->proyecto->id)
                    ->with('success', '¡Bienvenido! Te uniste a ' . $inv->proyecto->name);
            }
        }

        return redirect()->route('proyectos');
    }
}

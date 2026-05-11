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

    public function showPerfil()
    {
        return view('perfil', ['usuario' => Auth::user()]);
    }

    public function actualizarPerfil(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'username'    => 'required|unique:usuarios,username,' . $usuario->id,
            'description' => 'required',
            'email'       => 'required|email|unique:usuarios,email,' . $usuario->id,
        ]);

        if ($request->filled('password_nuevo')) {
            $passwordActualConcatenada = $usuario->username . $request->password_actual;
            if (!Hash::check($passwordActualConcatenada, $usuario->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.'])->withInput();
            }
        }

        $usuario->username    = $request->username;
        $usuario->description = $request->description;
        $usuario->email       = $request->email;

        if ($request->filled('password_nuevo')) {
            $usuario->password = Hash::make($request->username . $request->password_nuevo);
        }

        $usuario->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
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

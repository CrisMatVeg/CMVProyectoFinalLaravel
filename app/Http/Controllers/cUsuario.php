<?php

namespace App\Http\Controllers;

use App\Models\ProyectoAcceso;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Gestiona el ciclo de vida del usuario: registro, autenticación, perfil.
 *
 * Nota sobre contraseñas: se concatena username+password antes de hashear.
 * Esto garantiza que dos usuarios con la misma contraseña generen hashes diferentes,
 * dado que el username es único por definición.
 */
class cUsuario extends Controller
{
    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Muestra el formulario de registro.
     * Si se recibe un código de invitación por query string, lo carga y guarda en sesión.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
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

    /**
     * Autentica al usuario con sus credenciales.
     * El hash almacenado corresponde a username+password concatenados.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user               = Usuario::where('username', $request->username)->first();
        $passwordConcatenada = $request->username . $request->password;

        if ($user && Hash::check($passwordConcatenada, $user->password)) {
            Auth::login($user);
            return redirect()->route('proyectos');
        }

        return back()->withErrors(['username' => 'Credenciales incorrectas']);
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /**
     * Muestra la vista del perfil del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function showPerfil()
    {
        return view('perfil', ['usuario' => Auth::user()]);
    }

    /**
     * Actualiza los datos del perfil del usuario autenticado.
     * Si se proporciona nueva contraseña, verifica la actual antes de cambiarla.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarPerfil(Request $request)
    {
        /** @var Usuario $usuario */
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

    /**
     * Registra un nuevo usuario.
     * Si hay un código de invitación en sesión o en el request, lo procesa
     * para unir al usuario al proyecto correspondiente tras el registro.
     * El usuario + los accesos de invitación se crean dentro de una transacción.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registro(Request $request)
    {
        $request->validate([
            'username'    => 'required|unique:usuarios,username',
            'description' => 'required',
            'email'       => 'required|email|unique:usuarios,email',
            'password'    => 'required|min:4',
        ]);

        $passwordConcatenada = $request->username . $request->password;

        $usuario = DB::transaction(function () use ($request, $passwordConcatenada) {
            $u              = new Usuario();
            $u->username    = $request->username;
            $u->description = $request->description;
            $u->email       = $request->email;
            $u->password    = Hash::make($passwordConcatenada);
            $u->save();
            return $u;
        });

        Auth::login($usuario);

        $codigoPendiente = $request->session()->pull('invite_code')
                        ?? $request->input('invite_code');

        if ($codigoPendiente) {
            $inv = \App\Models\Invitacion::with('proyecto')
                ->where('codigo', $codigoPendiente)->first();

            if ($inv && $inv->esValida()) {
                DB::transaction(function () use ($inv, $usuario) {
                    $inv->increment('uses_count');

                    foreach ($inv->areas ?? [] as $tipoId) {
                        ProyectoAcceso::firstOrCreate([
                            'proyecto_id' => $inv->proyecto->id,
                            'user_id'     => $usuario->id,
                            'tipo_id'     => $tipoId,
                        ]);
                    }
                });

                return redirect()->route('proyecto', $inv->proyecto->id)
                    ->with('success', '¡Bienvenido! Te uniste a ' . $inv->proyecto->name);
            }
        }

        return redirect()->route('proyectos');
    }
}

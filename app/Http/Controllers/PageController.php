<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        return view('index'); // muestra index.blade.php
    }

    public function login()
    {
        return view('login'); // muestra login.blade.php
    }

    public function registro()
    {
        return view('registro'); // muestra registro.blade.php
    }

    public function proyectos()
    {
        $usuario = Auth::user();
        return view('proyectos', [
            'usuario' => $usuario,
            'proyectos' => $usuario->proyectos()->latest()->get(),
        ]);
    }

    public function proyecto()
    {
        return view('proyecto'); // muestra proyecto.blade.php
    }

    public function procesarLogin(Request $request)
    {
        return redirect()->route('proyectos');
    }

    public function procesarRegistro(Request $request)
    {
        return redirect()->route('proyectos');
    }
}

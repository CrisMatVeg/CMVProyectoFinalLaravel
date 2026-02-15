<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;
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

    public function proyecto($id)
    {
        // Cargar proyecto con departamentos, tareas y usuarios de cada departamento
        $proyecto = Proyecto::with(['departamentos.usuarios', 'departamentos.tareas'])->findOrFail($id);

        // Estadísticas generales del proyecto
        $numMiembros = $proyecto->departamentos
            ->flatMap(fn($dep) => $dep->usuarios) // todos los usuarios de todos los departamentos
            ->unique('id')                        // no contar repetidos
            ->count();

        $totalTareas = $proyecto->departamentos
            ->flatMap(fn($dep) => $dep->tareas)
            ->count();

        $tareasFinalizadas = $proyecto->departamentos
            ->flatMap(fn($dep) => $dep->tareas)
            ->where('estado', 'finalizado')
            ->count();

        $progreso = $totalTareas ? round(($tareasFinalizadas / $totalTareas) * 100) : 0;

        return view('proyecto', compact('proyecto', 'numMiembros', 'progreso'));
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

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

    public function proyecto($id)
    {
        $proyecto = Proyecto::with(['departamentos.tareas', 'miembros'])->findOrFail($id);

        // Estadísticas generales
        $numMiembros = $proyecto->departamentos
            ->flatMap(fn($dep) => $dep->usuarios) // todos los usuarios de todos los departamentos
            ->unique('id')                        // no contar repetidos
            ->count();
        $numTareas = $proyecto->departamentos
            ->flatMap(fn($dep) => $dep->tareas)
            ->count();
        $totalTareas = $numTareas;
        $tareasFinalizadas = $proyecto->departamentos
            ->flatMap(fn($dep) => $dep->tareas)
            ->where('estado', 'finalizado')
            ->count();

        $progreso = $totalTareas ? round(($tareasFinalizadas / $totalTareas) * 100) : 0;

        return view('proyecto', compact('proyecto', 'numMiembros', 'numProyectos', 'progreso'));
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

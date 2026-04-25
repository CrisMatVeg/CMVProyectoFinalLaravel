<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;
use App\Models\Tarea;
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
        $proyecto = Proyecto::with(['tareas.tipo', 'tareas.status', 'tareas.usuarios'])->findOrFail($id);
        $tipos    = \App\Models\Tipo::all();
        $usuario  = Auth::user();

        $numMiembros = $proyecto->tareas
            ->flatMap(fn($tarea) => $tarea->usuarios)
            ->unique('id')
            ->count();

        $totalTareas = $proyecto->tareas->count();

        $tareasFinalizadas = $proyecto->tareas
            ->filter(fn($t) => $t->status && $t->status->name === 'Terminada')
            ->count();

        $progreso = $totalTareas ? round(($tareasFinalizadas / $totalTareas) * 100) : 0;

        $tiposAccesibles = \App\Models\ProyectoAcceso::where('proyecto_id', $id)
            ->where('user_id', $usuario->id)
            ->pluck('tipo_id')
            ->toArray();

        // Sin rows → acceso legacy completo (todos los tipos)
        if (empty($tiposAccesibles)) {
            $tiposAccesibles = $tipos->pluck('id')->toArray();
        }

        return view('proyecto', compact('proyecto', 'numMiembros', 'progreso', 'tipos', 'totalTareas', 'tiposAccesibles'));
    }

    public function gantt($id)
    {
        $proyecto = Proyecto::with([
            'tareas.tipo',
            'tareas.status',
            'tareas.dependencias.status',
        ])->findOrFail($id);

        $tipos   = \App\Models\Tipo::all();
        $estados = \App\Models\Estado::all();

        $tareasJson = $proyecto->tareas->map(function ($t) {
            $blocked = $t->isBlocked();
            return [
                'id'           => $t->id,
                'title'        => $t->title,
                'tipo'         => $t->tipo   ? $t->tipo->name   : '—',
                'estado'       => $t->status ? $t->status->name : '—',
                'start'        => $t->start_date,
                'end'          => $t->end_date,
                'hours'        => $t->estimated_hours,
                'is_milestone' => (bool) $t->is_milestone,
                'depends_on'   => $t->dependencias->pluck('id')->toArray(),
                'blocked'      => $blocked,
            ];
        })->values();

        return view('gantt', compact('proyecto', 'tipos', 'estados', 'tareasJson'));
    }

    public function tareasPorTipo($proyectoId, $tipoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $tipo     = \App\Models\Tipo::findOrFail($tipoId);
        $estados  = \App\Models\Estado::all();

        $usuarios = Usuario::whereHas('proyectosParticipando', fn($q) => $q->where('proyecto_id', $proyectoId))
            ->where('tipo_id', $tipoId)
            ->get();

        $tareas = Tarea::where('project_id', $proyectoId)
            ->where('type_id', $tipoId)
            ->with(['status', 'usuarios', 'dependencias.status'])
            ->latest()
            ->get();

        // Lista de todas las tareas del proyecto para el selector de dependencias
        $todasLasTareasJson = Tarea::where('project_id', $proyectoId)
            ->with('tipo')
            ->orderBy('title')
            ->get()
            ->map(function ($t) {
                return [
                    'id'    => $t->id,
                    'title' => $t->title,
                    'tipo'  => $t->tipo ? $t->tipo->name : '—',
                ];
            })->values();

        $totalTareas = $tareas->count();
        $finalizadas = $tareas->filter(fn($t) => $t->status && $t->status->name === 'Terminada')->count();
        $progreso    = $totalTareas ? round(($finalizadas / $totalTareas) * 100) : 0;

        return view('tareas', compact(
            'proyecto', 'tipo', 'tareas', 'estados',
            'usuarios', 'progreso', 'todasLasTareasJson'
        ));
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

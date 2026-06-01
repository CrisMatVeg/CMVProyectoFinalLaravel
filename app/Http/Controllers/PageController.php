<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de páginas: renderiza las vistas principales de la aplicación.
 * Centraliza la carga de datos para cada pantalla del proyecto.
 */
class PageController extends Controller
{
    /**
     * Página de inicio pública.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Lista todos los proyectos del usuario autenticado:
     * los que creó y los en los que participa como miembro invitado.
     *
     * @return \Illuminate\View\View
     */
    public function proyectos()
    {
        $usuario = Auth::user();
        $proyectosCreados = $usuario->proyectos()->latest()->get();
        $proyectosParticipando = $usuario->proyectosParticipando()
            ->where('created_by', '!=', $usuario->id)
            ->latest()
            ->get();

        return view('proyectos', [
            'usuario'               => $usuario,
            'proyectos'             => $proyectosCreados,
            'proyectosParticipando' => $proyectosParticipando,
        ]);
    }

    /**
     * Vista principal de un proyecto: tareas, progreso y tipos accesibles por el usuario.
     * Requiere ser miembro del proyecto.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function proyecto($id)
    {
        $proyecto = Proyecto::with(['tareas.tipo', 'tareas.status', 'tareas.usuarios'])->findOrFail($id);
        $this->verificarMiembro($proyecto);

        $tipos    = \App\Models\Tipo::all();
        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();

        $numMiembros       = $proyecto->miembros()->count();
        $totalTareas       = $proyecto->tareas->count();
        $tareasFinalizadas = $proyecto->tareas
            ->filter(fn($t) => $t->status && $t->status->name === 'Terminada')
            ->count();
        $progreso = $totalTareas ? round(($tareasFinalizadas / $totalTareas) * 100) : 0;

        // Tipos a los que tiene acceso el usuario; si no hay registros específicos, accede a todos
        $tiposAccesibles = \App\Models\ProyectoAcceso::where('proyecto_id', $id)
            ->where('user_id', $usuario->id)
            ->pluck('tipo_id')
            ->toArray();

        if (empty($tiposAccesibles)) {
            $tiposAccesibles = $tipos->pluck('id')->toArray();
        }

        return view('proyecto', compact('proyecto', 'numMiembros', 'progreso', 'tipos', 'totalTareas', 'tiposAccesibles'));
    }

    /**
     * Vista del diagrama Gantt de las tareas del proyecto.
     * Requiere ser miembro del proyecto.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function gantt($id)
    {
        $proyecto = Proyecto::with([
            'tareas.tipo',
            'tareas.status',
            'tareas.dependencias.status',
            'tareas.usuarios',
        ])->findOrFail($id);
        $this->verificarMiembro($proyecto);

        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();
        $esOwner  = $proyecto->created_by === $usuario->id;

        $tipos   = \App\Models\Tipo::all();
        $estados = \App\Models\Estado::all();

        $tareasJson = $proyecto->tareas->map(function ($t) use ($usuario) {
            return [
                'id'             => $t->id,
                'title'          => $t->title,
                'tipo'           => $t->tipo   ? $t->tipo->name   : '—',
                'estado'         => $t->status ? $t->status->name : '—',
                'start'          => $t->start_date,
                'end'            => $t->end_date,
                'hours'          => $t->estimated_hours,
                'is_milestone'   => (bool) $t->is_milestone,
                'depends_on'     => $t->dependencias->pluck('id')->toArray(),
                'blocked'        => $t->isBlocked(),
                'assigned_to_me' => $t->usuarios->contains('id', $usuario->id),
            ];
        })->values();

        return view('gantt', compact('proyecto', 'tipos', 'estados', 'tareasJson', 'esOwner'));
    }

    /**
     * Vista del calendario de tareas del proyecto.
     * Requiere ser miembro del proyecto.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function calendario($id)
    {
        $proyecto = Proyecto::with(['tareas.tipo', 'tareas.status', 'tareas.usuarios'])->findOrFail($id);
        $this->verificarMiembro($proyecto);

        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();
        $esOwner  = $proyecto->created_by === $usuario->id;

        $tipos   = \App\Models\Tipo::all();
        $estados = \App\Models\Estado::all();

        $tareasJson = $proyecto->tareas->map(function ($t) use ($usuario) {
            return [
                'id'             => $t->id,
                'title'          => $t->title,
                'description'    => $t->description,
                'tipo'           => $t->tipo   ? $t->tipo->name   : '—',
                'estado'         => $t->status ? $t->status->name : '—',
                'start'          => $t->start_date,
                'end'            => $t->end_date,
                'hours'          => $t->estimated_hours,
                'is_milestone'   => (bool) $t->is_milestone,
                'assigned_to_me' => $t->usuarios->contains('id', $usuario->id),
            ];
        })->values();

        return view('calendario', compact('proyecto', 'tipos', 'estados', 'tareasJson', 'esOwner'));
    }

    /**
     * Vista de gestión de miembros de un proyecto.
     * Solo accesible para el owner del proyecto.
     * Muestra accesos por tipo de cada miembro.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function miembros($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();

        if ($proyecto->created_by !== $usuario->id) {
            return redirect()->route('proyecto', $id);
        }

        // Cargar miembros (excluyendo al owner) con sus tareas y accesos por tipo
        $miembros = $proyecto->miembros()
            ->where('id', '!=', $proyecto->created_by)
            ->with(['tareas' => fn($q) => $q->where('project_id', $id)->with('tipo', 'status')])
            ->get()
            ->each(function ($m) use ($id) {
                // N+1 conocido: se acepta dado el bajo número de miembros típico por proyecto
                $m->accesosProyecto = \App\Models\ProyectoAcceso::where('proyecto_id', $id)
                    ->where('user_id', $m->id)
                    ->with('tipo')
                    ->get();
            });

        return view('miembros', compact('proyecto', 'miembros'));
    }

    /**
     * Vista de tareas filtradas por área (tipo) para un proyecto.
     * El owner ve todas las tareas del área; los miembros solo las que tienen asignadas.
     * Protegida por el middleware area.access que verifica que el usuario
     * tenga acceso al tipo solicitado.
     *
     * @param int $proyectoId
     * @param int $tipoId
     * @return \Illuminate\View\View
     */
    public function tareasPorTipo($proyectoId, $tipoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $tipo     = \App\Models\Tipo::findOrFail($tipoId);
        $estados  = \App\Models\Estado::all();
        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();

        $esOwner = $proyecto->created_by === $usuario->id;

        $todasLasTareas = Tarea::where('project_id', $proyectoId)
            ->where('type_id', $tipoId)
            ->with(['status', 'usuarios', 'dependencias.status', 'notas.usuario'])
            ->latest()
            ->get();

        // Estadísticas siempre sobre el total del área
        $totalTareas = $todasLasTareas->count();
        $totalHoras  = $todasLasTareas->sum('estimated_hours');
        $finalizadas = $todasLasTareas->filter(fn($t) => $t->status && $t->status->name === 'Terminada')->count();
        $progreso    = $totalTareas ? round(($finalizadas / $totalTareas) * 100) : 0;

        // Miembros no-owner: solo ven las tareas donde están asignados
        $tareas = $esOwner
            ? $todasLasTareas
            : $todasLasTareas->filter(fn($t) => $t->usuarios->contains('id', $usuario->id))->values();

        // Todas las tareas del proyecto para el selector de dependencias
        $todasLasTareasJson = Tarea::where('project_id', $proyectoId)
            ->with('tipo')
            ->orderBy('title')
            ->get()
            ->map(fn($t) => [
                'id'    => $t->id,
                'title' => $t->title,
                'tipo'  => $t->tipo ? $t->tipo->name : '—',
            ])->values();

        // Usuarios del área disponibles para asignación
        $userIdsDelTipo = \App\Models\ProyectoAcceso::where('proyecto_id', $proyectoId)
            ->where('tipo_id', $tipoId)
            ->pluck('user_id')
            ->toArray();

        $ownerId  = $proyecto->created_by;
        $usuarios = empty($userIdsDelTipo)
            ? $proyecto->miembros()->where('id', '!=', $ownerId)->get()
            : $proyecto->miembros()->whereIn('id', $userIdsDelTipo)->where('id', '!=', $ownerId)->get();

        return view('tareas', compact(
            'proyecto', 'tipo', 'tareas', 'estados',
            'usuarios', 'progreso', 'todasLasTareasJson', 'esOwner',
            'totalTareas', 'totalHoras'
        ));
    }
}

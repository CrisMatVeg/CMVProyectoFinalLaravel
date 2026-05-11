<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | {{ $tipo->name }} - {{ $proyecto->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .task-card {
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            min-width: 0;
            overflow: hidden;
        }

        /* Tarea bloqueada */
        .task-card.blocked {
            opacity: 0.55;
            filter: grayscale(50%);
        }
        .task-card.blocked::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                transparent,
                transparent 6px,
                rgba(0,0,0,.08) 6px,
                rgba(0,0,0,.08) 12px
            );
            border-radius: inherit;
            pointer-events: none;
        }

        /* Tarea milestone */
        .task-card.milestone {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px var(--accentwop2);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .task-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background: var(--usercolor);
            border: 1px solid var(--border);
            color: var(--muted);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            color: var(--white);
            border-color: var(--accent);
        }

        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }

        .assigned-users {
            display: flex;
            margin-left: 8px;
        }

        .user-mini-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--accent);
            border: 2px solid var(--cardgrey);
            margin-left: -8px;
            display: grid;
            place-items: center;
            font-size: 10px;
            color: white;
            position: relative;
        }

        .user-mini-avatar:first-child { margin-left: 0; }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            place-items: center;
            z-index: 100;
            backdrop-filter: blur(4px);
        }

        .modal.active { display: grid; }

        .modal-content {
            background: var(--cardgrey);
            width: 90%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 32px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .task-badge {
            font-size: 10px;
            background: var(--accentwop2);
            color: var(--accent);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .milestone-badge {
            font-size: 10px;
            background: rgba(251,191,36,.2);
            color: #fbbf24;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .blocked-badge {
            font-size: 10px;
            background: rgba(239,68,68,.15);
            color: #ef4444;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .task-card .main-card-title {
            overflow-wrap: break-word;
            word-break: break-word;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .task-card .task-desc {
            overflow-wrap: break-word;
            word-break: break-word;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        /* Selector de dependencias */
        .dep-list {
            max-height: 160px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: var(--usercolor);
        }

        .dep-group-label {
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 4px 4px 2px;
            font-weight: 700;
        }

        .dep-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 6px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: background .15s;
        }

        .dep-item:hover { background: rgba(255,255,255,.05); }
        .dep-item input[type="checkbox"] { accent-color: var(--accent); flex-shrink: 0; }

        /* Toggle milestone */
        .milestone-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            background: var(--usercolor);
            transition: border-color .2s;
            margin-bottom: 16px;
        }
        .milestone-toggle:hover { border-color: #fbbf24; }
        .milestone-toggle input { accent-color: #fbbf24; width: 16px; height: 16px; }
        .milestone-toggle span { font-size: 13px; }
        .milestone-toggle small { font-size: 11px; color: var(--muted); display: block; }

        .blocked-info {
            font-size: 11px;
            color: #ef4444;
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 6px;
            padding: 6px 10px;
            display: flex;
            gap: 6px;
            align-items: flex-start;
        }
    </style>
</head>

@php
    $colorMap = [
        'Desarrollo' => 'teal',
        'Diseño'     => 'magenta',
        'Audio'      => 'blue',
        'Narrativa'  => 'green',
        'Marketing'  => 'purple',
        'Arte'       => 'orange',
    ];
    $colorClass = $colorMap[$tipo->name] ?? 'teal';
@endphp

<body class="{{ $colorClass }}">
    <aside class="sidebar">
        <div class="logo pixel-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>
        <nav class="menu">
            <span class="menu-section">Proyecto</span>
            <a href="{{ route('proyecto', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-chevron-left"></i></span>
                <span>Volver al Proyecto</span>
            </a>
            <div class="menu-item hover-lift active">
                <span class="menu-icon"><i class="fa-solid fa-list-check"></i></span>
                <span>{{ $tipo->name }}</span>
            </div>
            <a href="{{ route('proyecto.gantt', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-chart-gantt"></i></span>
                <span>Gantt</span>
            </a>
            <a href="{{ route('proyecto.calendario', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span>Calendario</span>
            </a>
            <a href="{{ route('proyecto.archivos', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
                <span>Archivos</span>
            </a>
            <a href="{{ route('proyecto.predefinicion', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                <span>Datos predefinidos</span>
            </a>
            @if($esOwner)
            <a href="{{ route('proyecto.miembros', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                <span>Miembros</span>
            </a>
            @endif
            <span class="menu-section">General</span>
            <a href="{{ route('proyectos') }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
                <span>Proyectos</span>
            </a>
        </nav>

        <div class="sidebar-user hover-lift">
            <a href="{{ route('perfil') }}" class="sidebar-user-link">
                <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                <div class="user-info">
                    <strong>{{ auth()->user()->username }}</strong>
                    <span class="title-gradient">{{ $esOwner ? 'Owner' : 'Miembro' }}</span>
                </div>
            </a>
            <button class="theme-toggle-btn" onclick="window.toggleTheme()" title="Cambiar tema">
                <i class="fa-solid fa-circle-half-stroke"></i>
            </button>
            <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                @csrf
                <button type="submit" class="logout">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
                </button>
            </form>
        </div>
    </aside>

    <main>
        <div class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1 class="title-gradient">{{ $tipo->name }}</h1>
                    <p class="subtitle">{{ $proyecto->name }} • Listado de tareas y seguimiento</p>
                </div>
                @if($esOwner)
                <button class="btn-primary hover-lift" onclick="openModal('modal-create')">
                    <i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Nueva Tarea
                </button>
                @endif
            </div>

            <section class="stats" style="margin: 24px 0;">
                <div class="card hover-lift">
                    <div class="stat-icon"><i class="fa-solid fa-tasks"></i></div>
                    <div class="stat-title">Tareas Totales</div>
                    <div class="stat-value">{{ $totalTareas }}</div>
                </div>
                <div class="card hover-lift">
                    <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                    <div class="stat-title">Horas Estimadas</div>
                    <div class="stat-value">{{ $totalHoras }}h</div>
                </div>
                <div class="card hover-lift">
                    <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="stat-title">Progreso</div>
                    <div class="stat-value">{{ $progreso }}%</div>
                </div>
            </section>

            <!-- GRID DE TAREAS -->
            <section class="task-grid">
                @forelse($tareas as $tarea)
                    @php
                        $isBlocked    = $tarea->isBlocked();
                        $isMilestone  = $tarea->is_milestone;
                        $blockingList = $isBlocked
                            ? $tarea->dependencias
                                ->filter(fn($d) => !$d->status || $d->status->name !== 'Terminada')
                                ->pluck('title')->implode(', ')
                            : '';
                    @endphp
                    <div class="card task-card hover-lift {{ $isBlocked ? 'blocked' : '' }} {{ $isMilestone ? 'milestone' : '' }}">

                        <div class="task-header">
                            <div style="display:flex;flex-wrap:wrap;gap:5px;align-items:center;">
                                @if($isMilestone)
                                    <span class="milestone-badge">
                                        <i class="fa-solid fa-diamond"></i> Milestone
                                    </span>
                                @endif
                                @if($isBlocked)
                                    <span class="blocked-badge">
                                        <i class="fa-solid fa-lock"></i> Bloqueada
                                    </span>
                                @else
                                    <span class="status {{ $tarea->status->name === 'Pendiente' ? 'todo' : ($tarea->status->name === 'En Proceso' ? 'wip' : 'done') }}">
                                        {{ $tarea->status->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="task-actions">
                                <button class="action-btn" title="Asignar Usuario" onclick="openAssignModal({{ $tarea->id }}, '{{ $tarea->title }}')">
                                    <i class="fa-solid fa-user-plus"></i>
                                </button>
                                @if($esOwner)
                                <button class="action-btn" title="Editar"
                                    onclick="openEditModal(
                                        {{ $tarea->id }},
                                        @js($tarea->title),
                                        @js($tarea->description ?? ''),
                                        {{ $tarea->status_id }},
                                        {{ $tarea->estimated_hours ?? 0 }},
                                        {{ $isMilestone ? 'true' : 'false' }},
                                        @js($tarea->start_date ?? ''),
                                        @js($tarea->end_date ?? ''),
                                        @js($tarea->dependencias->pluck('id')->toArray())
                                    )">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn" title="Eliminar" style="color: #ef4444;"
                                    onclick="openDeleteModal({{ $tarea->id }}, @js($tarea->title))">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </div>

                        @if($isBlocked)
                            <div class="blocked-info">
                                <i class="fa-solid fa-circle-exclamation" style="margin-top:1px;flex-shrink:0;"></i>
                                <span>Esperando: <strong>{{ $blockingList }}</strong></span>
                            </div>
                        @endif

                        <h3 class="main-card-title">{{ $tarea->title }}</h3>
                        <p class="subtitle task-desc" style="font-size: 13px; margin-bottom: 8px;">{{ $tarea->description }}</p>

                        <div class="task-meta">
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <div class="task-badge">
                                    <i class="fa-solid fa-hourglass-half"></i> {{ $tarea->estimated_hours }}h
                                </div>
                                @if($tarea->start_date)
                                    <div class="task-badge" style="background:rgba(99,102,241,.2);color:#818cf8;">
                                        <i class="fa-solid fa-calendar"></i> {{ \Carbon\Carbon::parse($tarea->start_date)->format('d/m') }}
                                    </div>
                                @endif
                            </div>
                            <div class="assigned-users">
                                @foreach($tarea->usuarios as $u)
                                    <div class="user-mini-avatar" title="{{ $u->username }}">
                                        {{ strtoupper(substr($u->username, 0, 1)) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 48px;">
                        <i class="fa-solid fa-clipboard-list fa-3x" style="color: var(--muted); margin-bottom: 16px;"></i>
                        @if($esOwner)
                            <p class="subtitle">No hay tareas en esta categoría. ¡Crea la primera!</p>
                        @else
                            <p class="subtitle">No tienes tareas asignadas en este departamento.</p>
                        @endif
                    </div>
                @endforelse
            </section>
        </div>

        <footer class="footer">
            <p>© 2025 PIXEL. Todos los derechos reservados.</p>
            <div class="footer-links"><a href="#">Privacidad</a><a href="#">Términos</a></div>
        </footer>
    </main>

    <!-- ═══════════════════════ MODAL CREAR ═══════════════════════ -->
    <div id="modal-create" class="modal">
        <div class="modal-content">
            <h2 class="title-gradient" style="margin-bottom: 20px;">Nueva Tarea</h2>
            <form action="{{ route('tareas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="type_id" value="{{ $tipo->id }}">

                <!-- Milestone toggle -->
                <label class="milestone-toggle" id="create-milestone-label">
                    <input type="checkbox" name="is_milestone" value="1" id="create-milestone-cb"
                           onchange="toggleMilestoneCreate(this.checked)">
                    <div>
                        <span><i class="fa-solid fa-diamond" style="color:#fbbf24;margin-right:6px;"></i>Marcar como Milestone</span>
                        <small>Hito clave del proyecto (entrega, evento, lanzamiento…)</small>
                    </div>
                </label>

                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="title" placeholder="Nombre de la tarea" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="description" rows="2" placeholder="¿Qué hay que hacer?"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Estado Inicial</label>
                        <select name="status_id" required>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="create-hours-group">
                        <label>Horas Estimadas</label>
                        <input type="number" name="estimated_hours" step="0.5" value="0" min="0">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="date" name="start_date">
                    </div>
                    <div class="form-group" id="create-enddate-group">
                        <label>Fecha Fin</label>
                        <input type="date" name="end_date">
                    </div>
                </div>

                <div class="form-group">
                    <label>Depende de (opcional)</label>
                    <div class="dep-list" id="create-dep-list"></div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 12px;">
                    <button type="submit" class="btn-primary" style="flex: 1;">Crear Tarea</button>
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════ MODAL ASIGNAR ═══════════════════════ -->
    <div id="modal-assign" class="modal">
        <div class="modal-content">
            <h2 class="title-gradient" style="margin-bottom: 8px;">Asignar Colaborador</h2>
            <p class="subtitle" id="assign-task-title" style="font-size: 14px;"></p>

            {{-- Asignar usuario individual --}}
            <form action="{{ route('tarea.usuario.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" id="assign-task-id">
                <div class="form-group">
                    <label>Usuario</label>
                    <select name="user_id" required>
                        <option value="">Selecciona un usuario...</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 12px;">
                    <button type="submit" class="btn-primary" style="flex: 1;">Asignar</button>
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-assign')">Cancelar</button>
                </div>
            </form>

            {{-- Separador --}}
            <div style="border-top:1px solid var(--border); margin: 20px 0; position:relative; text-align:center;">
                <span style="background:var(--cardgrey);padding:0 10px;font-size:11px;color:var(--muted);position:relative;top:-9px;letter-spacing:.5px;">O ASIGNAR DEPARTAMENTO COMPLETO</span>
            </div>

            {{-- Asignar todos los miembros del departamento actual --}}
            <form action="{{ route('tarea.departamento.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" id="assign-dept-task-id">
                <input type="hidden" name="tipo_id" value="{{ $tipo->id }}">
                <button type="submit" class="btn-secondary" style="width:100%;">
                    <i class="fa-solid fa-users" style="margin-right:6px;"></i>Asignar todo {{ $tipo->name }}
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════ MODAL EDITAR ═══════════════════════ -->
    <div id="modal-edit" class="modal">
        <div class="modal-content">
            <h2 class="title-gradient" style="margin-bottom: 20px;">Editar Tarea</h2>
            <form id="edit-form" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="type_id" value="{{ $tipo->id }}">

                <!-- Milestone toggle -->
                <label class="milestone-toggle" id="edit-milestone-label">
                    <input type="checkbox" name="is_milestone" value="1" id="edit-milestone-cb"
                           onchange="toggleMilestoneEdit(this.checked)">
                    <div>
                        <span><i class="fa-solid fa-diamond" style="color:#fbbf24;margin-right:6px;"></i>Marcar como Milestone</span>
                        <small>Hito clave del proyecto (entrega, evento, lanzamiento…)</small>
                    </div>
                </label>

                <div class="form-group">
                    <label>Título</label>
                    <input type="text" id="edit-title" name="title" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="edit-description" name="description" rows="2"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Estado</label>
                        <select id="edit-status-id" name="status_id" required>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="edit-hours-group">
                        <label>Horas Estimadas</label>
                        <input type="number" id="edit-estimated-hours" name="estimated_hours" step="0.5" min="0">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="date" id="edit-start-date" name="start_date">
                    </div>
                    <div class="form-group" id="edit-enddate-group">
                        <label>Fecha Fin</label>
                        <input type="date" id="edit-end-date" name="end_date">
                    </div>
                </div>

                <div class="form-group">
                    <label>Depende de (opcional)</label>
                    <div class="dep-list" id="edit-dep-list"></div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 12px;">
                    <button type="submit" class="btn-primary" style="flex: 1;">Guardar Cambios</button>
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════ MODAL ELIMINAR ═══════════════════════ -->
    <div id="modal-delete" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <i class="fa-solid fa-triangle-exclamation fa-2x" style="color: #ef4444; margin-bottom: 16px;"></i>
            <h2 style="margin-bottom: 8px; font-family: var(--font-title);">¿Eliminar tarea?</h2>
            <p class="subtitle" id="delete-task-name" style="margin-bottom: 24px; font-size: 14px;"></p>
            <form id="delete-form" method="POST">
                @csrf @method('DELETE')
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="submit" class="btn-primary" style="background: #ef4444; border-color: #ef4444; flex: 1;">
                        <i class="fa-solid fa-trash" style="margin-right: 6px;"></i>Eliminar
                    </button>
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-delete')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Todas las tareas del proyecto para el selector de dependencias
        const ALL_PROJECT_TASKS = @json($todasLasTareasJson);

        // ── Helpers de modal ───────────────────────────────────────────────
        function openModal(id)  { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) e.target.classList.remove('active');
        };

        // ── Dep list builder ──────────────────────────────────────────────
        function buildDepList(containerId, excludeId, checkedIds) {
            const container = document.getElementById(containerId);
            const grouped   = {};
            ALL_PROJECT_TASKS.forEach(t => {
                if (t.id === excludeId) return;
                if (!grouped[t.tipo]) grouped[t.tipo] = [];
                grouped[t.tipo].push(t);
            });

            let html = '';
            Object.entries(grouped).forEach(([tipo, tasks]) => {
                html += `<div class="dep-group-label">${tipo}</div>`;
                tasks.forEach(t => {
                    const checked = checkedIds.includes(t.id) ? 'checked' : '';
                    html += `
                        <label class="dep-item">
                            <input type="checkbox" name="depends_on[]" value="${t.id}" ${checked}>
                            ${t.title}
                        </label>`;
                });
            });

            container.innerHTML = html || '<span style="font-size:12px;color:var(--muted);padding:6px;">Sin otras tareas en el proyecto.</span>';
        }

        // ── Milestone toggle ──────────────────────────────────────────────
        function toggleMilestoneCreate(checked) {
            document.getElementById('create-hours-group').style.opacity  = checked ? '.4' : '1';
            document.getElementById('create-enddate-group').style.opacity = checked ? '.4' : '1';
        }
        function toggleMilestoneEdit(checked) {
            document.getElementById('edit-hours-group').style.opacity   = checked ? '.4' : '1';
            document.getElementById('edit-enddate-group').style.opacity = checked ? '.4' : '1';
        }

        // ── Abrir modales ─────────────────────────────────────────────────
        function openModal(id) { document.getElementById(id).classList.add('active'); }

        // Override para create: construir dep list
        const createBtn = document.querySelector('[onclick="openModal(\'modal-create\')"]');
        if (createBtn) {
            createBtn.addEventListener('click', function() {
                document.getElementById('create-milestone-cb').checked = false;
                toggleMilestoneCreate(false);
                buildDepList('create-dep-list', null, []);
                openModal('modal-create');
            });
        }

        function openAssignModal(id, title) {
            document.getElementById('assign-task-id').value = id;
            document.getElementById('assign-dept-task-id').value = id;
            document.getElementById('assign-task-title').innerText = 'Tarea: ' + title;
            openModal('modal-assign');
        }

        const tareaBaseUrl = "{{ url('tareas') }}";

        function openEditModal(id, title, description, statusId, estimatedHours, isMilestone, startDate, endDate, dependencyIds) {
            const form = document.getElementById('edit-form');
            form.action = tareaBaseUrl + '/' + id;

            document.getElementById('edit-title').value           = title;
            document.getElementById('edit-description').value     = description;
            document.getElementById('edit-status-id').value       = statusId;
            document.getElementById('edit-estimated-hours').value  = estimatedHours;
            document.getElementById('edit-start-date').value      = startDate || '';
            document.getElementById('edit-end-date').value        = endDate   || '';
            document.getElementById('edit-milestone-cb').checked  = isMilestone;
            toggleMilestoneEdit(isMilestone);

            buildDepList('edit-dep-list', id, dependencyIds || []);
            openModal('modal-edit');
        }

        function openDeleteModal(id, title) {
            document.getElementById('delete-form').action = tareaBaseUrl + '/' + id;
            document.getElementById('delete-task-name').textContent = '"' + title + '"';
            openModal('modal-delete');
        }
    </script>
</body>

</html>

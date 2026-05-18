<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | {{ $tipo->name }} - {{ $proyecto->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
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

        /* Tarea retrasada */
        .task-card.overdue {
            border-color: rgba(245,158,11,.5);
            box-shadow: 0 0 0 1px rgba(245,158,11,.15);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .task-badges {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .task-badges-row {
            display: flex;
            gap: 5px;
            align-items: center;
            min-height: 22px;
        }

        .task-badges-row--sub {
            min-height: 22px;
        }

        .overdue-badge--placeholder {
            display: inline-block;
            min-height: 22px;
            pointer-events: none;
        }

        .task-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            position: relative;
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

        /* Tarea retrasada */
        .overdue-badge {
            font-size: 10px;
            background: rgba(245,158,11,.2);
            color: #f59e0b;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .overdue-info {
            font-size: 11px;
            color: #f59e0b;
            background: rgba(245,158,11,.08);
            border: 1px solid rgba(245,158,11,.25);
            border-radius: 6px;
            padding: 6px 10px;
            display: flex;
            gap: 6px;
            align-items: flex-start;
        }

        /* Modal de notas */
        .nota-item {
            padding: 10px 14px;
            border-radius: 8px;
            background: var(--usercolor);
            border: 1px solid var(--border);
            margin-bottom: 8px;
        }

        .nota-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .nota-author {
            font-size: 12px;
            font-weight: bold;
            color: var(--accent);
        }

        .nota-date {
            font-size: 11px;
            color: var(--muted);
        }

        .nota-body {
            font-size: 13px;
            color: var(--white);
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .nota-delete-btn {
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 4px;
            transition: color .2s;
        }

        .nota-delete-btn:hover { color: #ef4444; }

        .notas-empty {
            font-size: 13px;
            color: var(--muted);
            text-align: center;
            padding: 20px 0;
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
                <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
                <span>Proyecto</span>
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
            <a href="{{ route('proyecto.foro', $proyecto->id) }}" class="menu-item hover-lift">
                <span class="menu-icon"><i class="fa-solid fa-comments"></i></span>
                <span>Foro</span>
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
                <span class="menu-icon"><i class="fa-solid fa-layer-group"></i></span>
                <span>Mis proyectos</span>
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

            <section class="stats" style="margin: 24px 0; grid-template-columns: repeat(3, 1fr);">
                <div class="card">
                    <div class="stat-icon"><i class="fa-solid fa-tasks"></i></div>
                    <div class="stat-title">Tareas Totales</div>
                    <div class="stat-value">{{ $totalTareas }}</div>
                </div>
                <div class="card">
                    <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                    <div class="stat-title">Horas Estimadas</div>
                    <div class="stat-value">{{ $totalHoras }}h</div>
                </div>
                <div class="card">
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
                        $isRetrasada  = $tarea->estaRetrasada();
                        $blockingList = $isBlocked
                            ? $tarea->dependencias
                                ->filter(fn($d) => !$d->status || $d->status->name !== 'Terminada')
                                ->pluck('title')->implode(', ')
                            : '';
                        $notasJson = $tarea->notas->map(fn($n) => [
                            'id'        => $n->id,
                            'autor'     => $n->usuario->username ?? '—',
                            'autorId'   => $n->user_id,
                            'contenido' => $n->contenido,
                            'fecha'     => $n->created_at->format('d/m/Y H:i'),
                        ])->values()->toArray();
                    @endphp
                    <div class="card task-card hover-lift {{ $isBlocked ? 'blocked' : '' }} {{ $isMilestone ? 'milestone' : '' }} {{ $isRetrasada ? 'overdue' : '' }}">

                        <div class="task-header">
                            <div class="task-badges">
                                <div class="task-badges-row">
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
                                <div class="task-badges-row task-badges-row--sub">
                                    @if($isRetrasada)
                                        <span class="overdue-badge">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Retrasada
                                        </span>
                                    @else
                                        <span class="overdue-badge--placeholder"></span>
                                    @endif
                                </div>
                            </div>
                            <div class="task-actions">
                                <button class="action-btn" title="Notas ({{ $tarea->notas->count() }})"
                                    data-task-id="{{ $tarea->id }}"
                                    data-task-title="{{ $tarea->title }}"
                                    data-notas="{{ json_encode($notasJson) }}"
                                    data-es-owner="{{ $esOwner ? '1' : '0' }}"
                                    data-user-id="{{ auth()->user()->id }}"
                                    onclick="openNotasModal(this)">
                                    <i class="fa-solid fa-note-sticky"></i>
                                    @if($tarea->notas->count() > 0)
                                        <span style="position:absolute;top:-4px;right:-4px;background:var(--accent);color:#fff;border-radius:50%;width:14px;height:14px;font-size:9px;display:grid;place-items:center;font-weight:700;">{{ $tarea->notas->count() }}</span>
                                    @endif
                                </button>
                                @if($esOwner)
                                <button class="action-btn" title="Asignar Usuario" onclick="openAssignModal({{ $tarea->id }}, '{{ $tarea->title }}')">
                                    <i class="fa-solid fa-user-plus"></i>
                                </button>
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
                    <input type="text" name="title" placeholder="Nombre de la tarea" required maxlength="150">
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="description" rows="2" placeholder="¿Qué hay que hacer?" maxlength="500"></textarea>
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
                        <label>Fecha Inicio *</label>
                        <input type="date" name="start_date" required>
                    </div>
                    <div class="form-group" id="create-enddate-group">
                        <label>Fecha Fin *</label>
                        <input type="date" name="end_date" required>
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
                    <input type="text" id="edit-title" name="title" required maxlength="150">
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="edit-description" name="description" rows="2" maxlength="500"></textarea>
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
                        <label>Fecha Inicio *</label>
                        <input type="date" id="edit-start-date" name="start_date" required>
                    </div>
                    <div class="form-group" id="edit-enddate-group">
                        <label>Fecha Fin *</label>
                        <input type="date" id="edit-end-date" name="end_date" required>
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

        // ── Modal de notas ────────────────────────────────────────────────
        const notaBaseUrl      = "{{ url('tarea') }}";
        const notaDeleteBase   = "{{ url('tarea/notas') }}";
        const csrfToken        = "{{ csrf_token() }}";

        function openNotasModal(btn) {
            const taskId        = btn.dataset.taskId;
            const taskTitle     = btn.dataset.taskTitle;
            const notas         = JSON.parse(btn.dataset.notas);
            const esOwner       = btn.dataset.esOwner === '1';
            const currentUserId = parseInt(btn.dataset.userId);

            document.getElementById('notas-task-title').textContent = taskTitle;
            document.getElementById('notas-form').action = notaBaseUrl + '/' + taskId + '/notas';

            const list = document.getElementById('notas-list');
            if (notas.length === 0) {
                list.innerHTML = '<p class="notas-empty"><i class="fa-solid fa-note-sticky" style="margin-right:6px;opacity:.4;"></i>Aún no hay notas en esta tarea.</p>';
            } else {
                list.innerHTML = notas.map(n => {
                    const canDelete = esOwner || n.autorId === currentUserId;
                    const deleteBtn = canDelete
                        ? `<button class="nota-delete-btn" onclick="deleteNota(${n.id})" title="Eliminar nota">
                                <i class="fa-solid fa-trash-can"></i>
                           </button>`
                        : '';
                    return `
                        <div class="nota-item" id="nota-item-${n.id}">
                            <div class="nota-header">
                                <span class="nota-author"><i class="fa-solid fa-user" style="margin-right:4px;opacity:.7;"></i>${escapeHtml(n.autor)}</span>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span class="nota-date">${n.fecha}</span>
                                    ${deleteBtn}
                                </div>
                            </div>
                            <div class="nota-body">${escapeHtml(n.contenido)}</div>
                        </div>`;
                }).join('');
            }

            openModal('modal-notas');
        }

        function deleteNota(notaId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = notaDeleteBase + '/' + notaId;
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">
                              <input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    </script>

    <!-- ═══════════════════════ MODAL NOTAS ═══════════════════════ -->
    <div id="modal-notas" class="modal">
        <div class="modal-content" style="max-width: 520px;">
            <h2 class="title-gradient" style="margin-bottom: 4px;">
                <i class="fa-solid fa-note-sticky" style="margin-right:8px;"></i>Notas de progreso
            </h2>
            <p class="subtitle" id="notas-task-title" style="font-size:13px;margin-bottom:20px;"></p>

            <div id="notas-list" style="max-height:320px;overflow-y:auto;margin-bottom:20px;"></div>

            <form id="notas-form" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom:10px;">
                    <label style="font-size:13px;">Añadir nota</label>
                    <textarea name="contenido" rows="3" placeholder="Escribe tu nota de progreso..." required
                        style="resize:vertical;"></textarea>
                </div>
                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn-primary" style="flex:1;">
                        <i class="fa-solid fa-paper-plane" style="margin-right:6px;"></i>Enviar nota
                    </button>
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-notas')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

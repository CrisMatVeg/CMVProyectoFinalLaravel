@extends('layouts.proyecto')

@php $activo = 'miembros'; @endphp

@section('titulo', 'Miembros — ' . $proyecto->name)

@push('estilos')
  <style>
    /* ── Lista de miembros ──────────────────────────── */
    .member-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .member-card {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow: hidden;
    }

    .member-row {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 16px 22px;
      cursor: pointer;
    }

    .member-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 17px;
      flex-shrink: 0;
      color: #fff;
      text-transform: uppercase;
    }

    /* Paleta de avatares de miembros (user_id % 8) */
    .member-avatar[data-avatar-index="0"] { background: #E040FB; }
    .member-avatar[data-avatar-index="1"] { background: #42A5F5; }
    .member-avatar[data-avatar-index="2"] { background: #26C6DA; }
    .member-avatar[data-avatar-index="3"] { background: #66BB6A; }
    .member-avatar[data-avatar-index="4"] { background: #AB47BC; }
    .member-avatar[data-avatar-index="5"] { background: #FFA726; }
    .member-avatar[data-avatar-index="6"] { background: #ef5350; }
    .member-avatar[data-avatar-index="7"] { background: #26a69a; }

    .member-info {
      flex: 1;
      min-width: 0;
    }

    .member-name {
      font-weight: 600;
      font-size: 14px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .member-desc {
      font-size: 12px;
      color: var(--muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .member-depts {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      justify-content: flex-end;
      max-width: 340px;
    }

    /* Badge de departamento — color via --tipo-color (definido en estilos.css) */
    .dept-badge {
      font-size: 11px;
      padding: 3px 11px;
      border-radius: 20px;
      border: 1px solid var(--tipo-color, #14b8a6);
      color: var(--tipo-color, #14b8a6);
      font-weight: 600;
      white-space: nowrap;
    }

    .no-dept {
      font-size: 12px;
      color: var(--muted);
    }

    .member-task-count {
      text-align: center;
      min-width: 54px;
      flex-shrink: 0;
    }

    .task-count-num {
      font-size: 19px;
      font-weight: 700;
    }

    .task-count-label {
      font-size: 11px;
      color: var(--muted);
    }

    .member-chevron {
      color: var(--muted);
      font-size: 12px;
      flex-shrink: 0;
      transition: transform .2s;
    }

    /* ── Panel expandible de tareas ─────────────────── */
    .member-tasks-panel {
      display: none;
      border-top: 1px solid var(--border);
      padding: 14px 22px;
    }

    .no-tasks-msg {
      font-size: 13px;
      color: var(--muted);
      text-align: center;
      padding: 12px 0;
    }

    .tasks-inner {
      display: flex;
      flex-direction: column;
      gap: 7px;
    }

    .task-row-item {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255,255,255,0.03);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 9px 13px;
    }

    /* Badge de tipo de tarea — color via --tipo-color */
    .task-tipo-badge {
      font-size: 11px;
      padding: 2px 9px;
      border-radius: 20px;
      border: 1px solid var(--tipo-color, #14b8a6);
      color: var(--tipo-color, #14b8a6);
      font-weight: 600;
      flex-shrink: 0;
    }

    .task-row-title {
      font-size: 13px;
      flex: 1;
    }

    .task-status-badge {
      font-size: 11px;
      padding: 2px 9px;
      background: rgba(255,255,255,0.06);
      border-radius: 6px;
      color: var(--muted);
      flex-shrink: 0;
    }

    .milestone-icon {
      font-size: 11px;
      color: #a855f7;
    }

    /* ── Estado vacío ───────────────────────────────── */
    .member-empty-state {
      background: rgba(255,255,255,0.03);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 52px;
      text-align: center;
    }

    .member-empty-icon {
      font-size: 2.2rem;
      color: var(--muted);
      margin-bottom: 16px;
      display: block;
      opacity: .4;
    }

    .member-empty-title {
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .member-empty-desc {
      color: var(--muted);
      font-size: 13px;
    }

    .btn-empty-cta {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 20px;
      text-decoration: none;
      font-size: 13px;
      padding: 9px 18px;
      width: auto;
    }
  </style>
@endpush

@section('contenido')

      <h1 class="title-gradient">Miembros</h1>
      <p class="subtitle">{{ $proyecto->name }}</p>

      <div class="counter-row">
        <span class="count-badge">
          <i class="fa-solid fa-users btn-icon-mr-sm"></i>
          {{ $miembros->count() }} {{ $miembros->count() === 1 ? 'miembro' : 'miembros' }}
        </span>
      </div>

      @if($miembros->isEmpty())
        <div class="member-empty-state">
          <i class="fa-solid fa-user-plus member-empty-icon"></i>
          <div class="member-empty-title">Nadie se ha unido todavía</div>
          <div class="member-empty-desc">Genera un enlace de invitación desde la vista del proyecto.</div>
          <a href="{{ route('proyecto', $proyecto->id) }}" class="btn-primary hover-lift btn-empty-cta">
            <i class="fa-solid fa-link"></i> Ir al proyecto
          </a>
        </div>
      @else
        <div class="member-list">
          @foreach($miembros as $miembro)
          <div class="member-card">

            <!-- Fila principal del miembro -->
            <div class="member-row" data-miembro-id="{{ $miembro->id }}" onclick="toggleMiembro(this)">

              <!-- Avatar con inicial -->
              <div class="member-avatar" data-avatar-index="{{ $miembro->id % 8 }}">
                {{ mb_substr($miembro->username, 0, 1) }}
              </div>

              <!-- Nombre y descripción -->
              <div class="member-info">
                <div class="member-name">{{ $miembro->username }}</div>
                @if($miembro->description)
                <div class="member-desc">{{ $miembro->description }}</div>
                @endif
              </div>

              <!-- Badges de departamentos con acceso -->
              <div class="member-depts">
                @forelse($miembro->accesosProyecto as $acceso)
                <span class="dept-badge" data-tipo="{{ $acceso->tipo->name }}">
                  {{ $acceso->tipo->name }}
                </span>
                @empty
                <span class="no-dept">Sin departamentos</span>
                @endforelse
              </div>

              <!-- Contador de tareas -->
              <div class="member-task-count">
                <div class="task-count-num">{{ $miembro->tareas->count() }}</div>
                <div class="task-count-label">{{ $miembro->tareas->count() === 1 ? 'tarea' : 'tareas' }}</div>
              </div>

              <!-- Chevron -->
              <div id="chevron-{{ $miembro->id }}" class="member-chevron">
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>

            <!-- Panel expandible: tareas asignadas -->
            <div id="tasks-{{ $miembro->id }}" class="member-tasks-panel">
              @if($miembro->tareas->isEmpty())
                <div class="no-tasks-msg">
                  <i class="fa-solid fa-inbox"></i>&nbsp; Sin tareas asignadas todavía
                </div>
              @else
                <div class="tasks-inner">
                  @foreach($miembro->tareas as $tarea)
                  <div class="task-row-item">
                    <span class="task-tipo-badge" data-tipo="{{ $tarea->tipo ? $tarea->tipo->name : '' }}">
                      {{ $tarea->tipo ? $tarea->tipo->name : '—' }}
                    </span>
                    <span class="task-row-title">{{ $tarea->title }}</span>
                    @if($tarea->status)
                    <span class="task-status-badge">{{ $tarea->status->name }}</span>
                    @endif
                    @if($tarea->is_milestone)
                    <i class="fa-solid fa-flag milestone-icon" title="Hito"></i>
                    @endif
                  </div>
                  @endforeach
                </div>
              @endif
            </div>

          </div>
          @endforeach
        </div>
      @endif

    </div>

    <x-footer />
@endsection

@push('scripts')
  <script>
    function toggleMiembro(el) {
      const id      = el.dataset.miembroId;
      const panel   = document.getElementById('tasks-'   + id);
      const chevron = document.getElementById('chevron-' + id);
      const open    = panel.style.display !== 'none';
      panel.style.display     = open ? 'none'  : 'block';
      chevron.style.transform = open ? ''      : 'rotate(180deg)';
    }
  </script>
@endpush

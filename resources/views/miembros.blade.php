<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | Miembros — {{ $proyecto->name }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>

@php
  $colorHex = [
    'Desarrollo' => '#14b8a6',
    'Diseño'     => '#ff00ff',
    'Audio'      => '#3b82f6',
    'Narrativa'  => '#4ade80',
    'Marketing'  => '#a855f7',
    'Arte'       => '#fb923c',
  ];
  $avatarPalette = ['#E040FB','#42A5F5','#26C6DA','#66BB6A','#AB47BC','#FFA726','#ef5350','#26a69a'];
@endphp

<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo pixel-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>
    <nav class="menu">
      <span class="menu-section">Proyecto</span>
      <a href="{{ route('proyecto', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-chevron-left"></i></span>
        <span>Volver al Proyecto</span>
      </a>
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
      <div class="menu-item hover-lift active">
        <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
        <span>Miembros</span>
      </div>
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
          <span class="title-gradient">Owner</span>
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

  <!-- MAIN -->
  <main>
    <div class="main-content">

      <h1 class="title-gradient">Miembros</h1>
      <p class="subtitle">{{ $proyecto->name }}</p>

      <div style="margin:20px 0 28px;">
        <span style="background:rgba(255,255,255,0.06);border:1px solid var(--border);border-radius:20px;padding:4px 14px;font-size:13px;color:var(--muted);">
          <i class="fa-solid fa-users" style="margin-right:6px;"></i>
          {{ $miembros->count() }} {{ $miembros->count() === 1 ? 'miembro' : 'miembros' }}
        </span>
      </div>

      @if($miembros->isEmpty())
        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--radius-lg);padding:52px;text-align:center;">
          <i class="fa-solid fa-user-plus" style="font-size:2.2rem;color:var(--muted);margin-bottom:16px;display:block;opacity:.4;"></i>
          <div style="font-size:15px;font-weight:600;margin-bottom:8px;">Nadie se ha unido todavía</div>
          <div style="color:var(--muted);font-size:13px;">Genera un enlace de invitación desde la vista del proyecto.</div>
          <a href="{{ route('proyecto', $proyecto->id) }}" class="btn-primary hover-lift"
             style="display:inline-flex;align-items:center;gap:8px;margin-top:20px;text-decoration:none;font-size:13px;padding:9px 18px;width:auto;">
            <i class="fa-solid fa-link"></i> Ir al proyecto
          </a>
        </div>
      @else
        <div style="display:flex;flex-direction:column;gap:8px;">
          @foreach($miembros as $miembro)
          @php $avatarColor = $avatarPalette[$miembro->id % count($avatarPalette)]; @endphp
          <div style="background:var(--cardgrey);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">

            <!-- Fila principal del miembro -->
            <div style="display:flex;align-items:center;gap:14px;padding:16px 22px;cursor:pointer;"
                 onclick="toggleMiembro({{ $miembro->id }})">

              <!-- Avatar con inicial -->
              <div style="width:42px;height:42px;border-radius:50%;background:{{ $avatarColor }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:17px;flex-shrink:0;color:#fff;text-transform:uppercase;">
                {{ mb_substr($miembro->username, 0, 1) }}
              </div>

              <!-- Nombre y descripción -->
              <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $miembro->username }}</div>
                @if($miembro->description)
                <div style="font-size:12px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $miembro->description }}</div>
                @endif
              </div>

              <!-- Badges de departamentos con acceso -->
              <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;max-width:340px;">
                @forelse($miembro->accesosProyecto as $acceso)
                @php $hex = $colorHex[$acceso->tipo->name] ?? '#14b8a6'; @endphp
                <span style="font-size:11px;padding:3px 11px;border-radius:20px;border:1px solid {{ $hex }};color:{{ $hex }};font-weight:600;white-space:nowrap;">
                  {{ $acceso->tipo->name }}
                </span>
                @empty
                <span style="font-size:12px;color:var(--muted);">Sin departamentos</span>
                @endforelse
              </div>

              <!-- Contador de tareas -->
              <div style="text-align:center;min-width:54px;flex-shrink:0;">
                <div style="font-size:19px;font-weight:700;">{{ $miembro->tareas->count() }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $miembro->tareas->count() === 1 ? 'tarea' : 'tareas' }}</div>
              </div>

              <!-- Chevron -->
              <div id="chevron-{{ $miembro->id }}" style="color:var(--muted);font-size:12px;flex-shrink:0;transition:transform .2s;">
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>

            <!-- Panel expandible: tareas asignadas -->
            <div id="tasks-{{ $miembro->id }}" style="display:none;border-top:1px solid var(--border);padding:14px 22px;">
              @if($miembro->tareas->isEmpty())
                <div style="font-size:13px;color:var(--muted);text-align:center;padding:12px 0;">
                  <i class="fa-solid fa-inbox"></i>&nbsp; Sin tareas asignadas todavía
                </div>
              @else
                <div style="display:flex;flex-direction:column;gap:7px;">
                  @foreach($miembro->tareas as $tarea)
                  @php $hex = $tarea->tipo ? ($colorHex[$tarea->tipo->name] ?? '#14b8a6') : '#14b8a6'; @endphp
                  <div style="display:flex;align-items:center;gap:10px;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:8px;padding:9px 13px;">
                    <span style="font-size:11px;padding:2px 9px;border-radius:20px;border:1px solid {{ $hex }};color:{{ $hex }};font-weight:600;flex-shrink:0;">
                      {{ $tarea->tipo ? $tarea->tipo->name : '—' }}
                    </span>
                    <span style="font-size:13px;flex:1;">{{ $tarea->title }}</span>
                    @if($tarea->status)
                    <span style="font-size:11px;padding:2px 9px;background:rgba(255,255,255,0.06);border-radius:6px;color:var(--muted);flex-shrink:0;">
                      {{ $tarea->status->name }}
                    </span>
                    @endif
                    @if($tarea->is_milestone)
                    <i class="fa-solid fa-flag" style="font-size:11px;color:#a855f7;" title="Hito"></i>
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

    <footer class="footer magenta">
      <p>© 2025 PIXEL. Todos los derechos reservados.</p>
      <div class="footer-links">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
        <a href="#">Contacto</a>
      </div>
    </footer>
  </main>

  <script>
    function toggleMiembro(id) {
      const panel   = document.getElementById('tasks-'   + id);
      const chevron = document.getElementById('chevron-' + id);
      const open    = panel.style.display !== 'none';
      panel.style.display     = open ? 'none'  : 'block';
      chevron.style.transform = open ? ''      : 'rotate(180deg)';
    }
  </script>
</body>
</html>

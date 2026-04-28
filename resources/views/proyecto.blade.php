<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | {{ $proyecto->name }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite('resources/css/app.css')
</head>

<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo title-gradient"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>

    <nav class="menu">
      <span class="menu-section">Proyecto</span>
      <div class="menu-item hover-lift active">
        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
        <span>Equipo</span>
      </div>
      <a href="{{ route('proyecto.gantt', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-chart-gantt"></i></span>
        <span>Gantt</span>
      </a>
      <a href="{{ route('proyecto.calendario', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
        <span>Calendario</span>
      </a>
      @if($proyecto->created_by === auth()->id())
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
      <a href="{{ route('perfil') }}" style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;text-decoration:none;color:inherit;">
        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
        <div class="user-info">
          <strong>{{ auth()->user()->username }}</strong>
          <span class="title-gradient">{{ $proyecto->created_by === auth()->id() ? 'Owner' : 'Miembro' }}</span>
        </div>
      </a>
      <form action="{{ route('logout') }}" method="POST" style="margin-left: auto;">
        @csrf
        <button type="submit" class="logout" style="background:none; border:none; color:inherit; cursor:pointer;">
          <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
        </button>
      </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <div class="main-content">
      <!-- Título del proyecto -->
      <h1 class="title-gradient">{{ $proyecto->name }}</h1>
      <p class="subtitle">{{ $proyecto->description }}</p>

      <div style="margin-top:16px;margin-bottom:32px;display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('proyecto.gantt', $proyecto->id) }}" class="btn-secondary hover-lift" style="font-size:13px;padding:8px 16px;width:auto;text-decoration:none;">
          <i class="fa-solid fa-chart-gantt"></i> Ver Gantt
        </a>
        <a href="{{ route('proyecto.calendario', $proyecto->id) }}" class="btn-secondary hover-lift" style="font-size:13px;padding:8px 16px;width:auto;text-decoration:none;">
          <i class="fa-solid fa-calendar-days"></i> Ver Calendario
        </a>
        @if($proyecto->created_by === auth()->id())
        <button type="button" id="open-invite-modal" class="btn-primary hover-lift" style="font-size:13px;padding:8px 16px;width:auto;">
          <i class="fa-solid fa-link"></i> Invitar personas
        </button>
        @endif
      </div>

      @if(session('success'))
      <div style="margin-bottom:20px;padding:9px 14px;background:rgba(0,200,150,0.1);border:1px solid var(--teal);border-radius:6px;font-size:13px;color:var(--teal);">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
      </div>
      @endif

      <!-- STATS GENERALES -->
      <section class="stats">
        <div class="card hover-lift teal">
          <div class="stat-icon"><i class="fa-solid fa-people-group fa-lg"></i></div>
          <div class="stat-title">Miembros</div>
          <div class="stat-value">{{ $numMiembros }}</div>
        </div>

        <div class="card hover-lift purple">
          <div class="stat-icon"><i class="fa-solid fa-folder-open fa-lg"></i></div>
          <div class="stat-title">Tareas</div>
          <div class="stat-value">{{ $totalTareas }}</div>
        </div>

        <div class="card hover-lift blue">
          <div class="stat-icon"><i class="fa-solid fa-bars-progress fa-lg"></i></div>
          <div class="stat-title">Progreso</div>
          <div class="stat-value">{{ $progreso }}%</div>
        </div>
      </section>

      <!-- DEPARTAMENTOS -->
      <section class="departments">
        <h2 class="title-gradient">Departamentos</h2>

        <div class="department-grid">
          @foreach($tipos->filter(fn($t) => in_array($t->id, $tiposAccesibles)) as $tipo)
            @php
              $tareasDelTipo = $proyecto->tareas->where('type_id', $tipo->id);
              $numMiembrosDep = $tareasDelTipo->flatMap->usuarios->unique('id')->count();
              $totalTareasDep = $tareasDelTipo->count();
              $tareasFinalizadasDep = $tareasDelTipo->filter(fn($t) => $t->status && $t->status->name === 'Terminada')->count();
              $progresoDep = $totalTareasDep ? round(($tareasFinalizadasDep / $totalTareasDep) * 100) : 0;

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

            <a href="{{ route('proyecto.tipo', [$proyecto->id, $tipo->id]) }}"
              class="card main-card hover-lift {{ $colorClass }}">
              <div class="main-card-header">
                <div class="main-card-icon">
                  @if($tipo->name === 'Desarrollo') <i class="fa-solid fa-code fa-lg"></i>
                  @elseif($tipo->name === 'Arte') <i class="fa-solid fa-palette fa-lg"></i>
                  @elseif($tipo->name === 'Narrativa') <i class="fa-solid fa-scroll fa-lg"></i>
                  @elseif($tipo->name === 'Diseño') <i class="fa-solid fa-gamepad fa-lg"></i>
                  @elseif($tipo->name === 'Audio') <i class="fa-solid fa-music fa-lg"></i>
                  @elseif($tipo->name === 'Marketing') <i class="fa-solid fa-comments-dollar fa-lg"></i>
                  @else <i class="fa-solid fa-circle fa-lg"></i>
                  @endif
                </div>
                <div>
                  <div class="main-card-title">{{ $tipo->name }}</div>
                  <div class="main-card-subtitle">Tareas de {{ strtolower($tipo->name) }} del proyecto</div>
                </div>
              </div>

              <div class="main-card-stats">
                <div class="main-card-stat">
                  <strong>{{ $numMiembrosDep }}</strong>
                  <span>Miembros</span>
                </div>
                <div class="main-card-stat">
                  <strong>{{ $totalTareasDep }}</strong>
                  <span>Tareas</span>
                </div>
                <div class="main-card-stat">
                  <strong>{{ $progresoDep }}%</strong>
                  <span>Progreso</span>
                </div>
              </div>

              <div class="main-card-tags">
                <div class="main-card-tag">Prioridad</div>
                <div class="main-card-tag">Sprint</div>
              </div>
            </a>
          @endforeach
        </div>
      </section>

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

  @if($proyecto->created_by === auth()->id())
  <!-- MODAL SELECCIÓN DE ÁREAS -->
  <div id="areas-invite-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.65);align-items:center;justify-content:center;z-index:9998;">
    <div style="background:var(--cardgrey);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;width:100%;max-width:460px;position:relative;box-shadow:0 8px 32px rgba(0,0,0,0.4);">

      <button id="close-areas-modal" style="position:absolute;top:14px;right:16px;background:none;border:none;color:var(--muted);font-size:1.1rem;cursor:pointer;line-height:1;">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <h2 class="title-gradient" style="margin-bottom:4px;">Invitar al proyecto</h2>
      <p style="color:var(--muted);font-size:13px;margin-bottom:20px;">Selecciona las áreas a las que tendrá acceso el invitado.</p>

      <form method="POST" action="{{ route('invitacion.generar', $proyecto->id) }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
          @foreach($tipos as $tipo)
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:13px;">
            <input type="checkbox" name="areas[]" value="{{ $tipo->id }}" style="accent-color:var(--magenta);">
            {{ $tipo->name }}
          </label>
          @endforeach
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <button type="submit" class="btn-primary hover-lift" style="font-size:13px;">Generar enlace</button>
          <button type="button" id="cancel-areas-modal" class="btn-secondary hover-lift" style="font-size:13px;">Cancelar</button>
        </div>
      </form>

    </div>
  </div>

  <script>
    const areasModal = document.getElementById('areas-invite-modal');
    const openBtn = document.getElementById('open-invite-modal');
    const closeAreasModal = () => areasModal.style.display = 'none';

    if (openBtn) {
      openBtn.addEventListener('click', () => areasModal.style.display = 'flex');
    }
    document.getElementById('close-areas-modal').addEventListener('click', closeAreasModal);
    document.getElementById('cancel-areas-modal').addEventListener('click', closeAreasModal);
    areasModal.addEventListener('click', e => { if (e.target === areasModal) closeAreasModal(); });
  </script>
  @endif

  @if(session('invite_url'))
  <style>
    #invite-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.65);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    #invite-modal .invite-card {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 28px;
      width: 100%;
      max-width: 460px;
      position: relative;
      box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }
    #invite-modal .invite-row {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 9px 12px;
      margin-top: 5px;
    }
    #invite-modal .invite-copy-btn {
      font-size: 12px;
      padding: 4px 10px;
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--white);
      cursor: pointer;
      white-space: nowrap;
      flex-shrink: 0;
      transition: border-color .2s;
    }
    #invite-modal .invite-copy-btn:hover {
      border-color: var(--magenta);
    }
  </style>

  <div id="invite-modal">
    <div class="invite-card">

      <button id="close-invite-modal" style="position:absolute;top:14px;right:16px;background:none;border:none;color:var(--muted);font-size:1.1rem;cursor:pointer;line-height:1;">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <h2 class="title-gradient" style="margin-bottom:4px;">Enlace de invitación</h2>
      <p style="color:var(--muted);font-size:13px;margin-bottom:20px;">Válido 7 días · Comparte el enlace o el código</p>

      <div style="display:flex;flex-direction:column;gap:14px;">

        <div>
          <label style="font-size:11px;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Enlace</label>
          <div class="invite-row">
            <i class="fa-solid fa-link" style="color:var(--muted);font-size:12px;flex-shrink:0;"></i>
            <span style="font-size:12px;color:var(--muted);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ session('invite_url') }}</span>
            <button type="button" class="invite-copy-btn" data-value="{{ session('invite_url') }}" data-label="Copiar enlace">Copiar enlace</button>
          </div>
        </div>

        <div>
          <label style="font-size:11px;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Código</label>
          <div class="invite-row">
            <i class="fa-solid fa-key" style="color:var(--muted);font-size:12px;flex-shrink:0;"></i>
            <span style="font-size:14px;color:var(--white);flex:1;letter-spacing:3px;font-weight:600;">{{ session('invite_code') }}</span>
            <button type="button" class="invite-copy-btn" data-value="{{ session('invite_code') }}" data-label="Copiar código">Copiar código</button>
          </div>
        </div>

      </div>

      <div style="margin-top:22px;display:flex;justify-content:flex-end;">
        <button id="close-invite-modal-btn" class="btn-secondary hover-lift" style="font-size:13px;">Cerrar</button>
      </div>

    </div>
  </div>

  <script>
    const inviteModal = document.getElementById('invite-modal');
    const closeInviteModal = () => inviteModal.style.display = 'none';

    document.getElementById('close-invite-modal').addEventListener('click', closeInviteModal);
    document.getElementById('close-invite-modal-btn').addEventListener('click', closeInviteModal);
    inviteModal.addEventListener('click', e => { if (e.target === inviteModal) closeInviteModal(); });

    document.querySelectorAll('.invite-copy-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        navigator.clipboard.writeText(btn.dataset.value).then(() => {
          const original = btn.dataset.label;
          btn.textContent = '¡Copiado!';
          setTimeout(() => btn.textContent = original, 2000);
        });
      });
    });
  </script>
  @endif

</body>

</html>
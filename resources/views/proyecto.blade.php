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
      <span class="menu-section">Menu</span>
      <div class="menu-item hover-lift active">
        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
        <span>Equipo</span>
      </div>
      <div class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
        <span>Proyectos</span>
      </div>
      <div class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
        <span>Calendario</span>
      </div>
      <span class="menu-section">Sistema</span>
      <div class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
        <span>Ajustes</span>
      </div>
    </nav>

    <div class="sidebar-user hover-lift">
      <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
      <div class="user-info">
        <strong>{{ auth()->user()->name }}</strong>
        <span class="title-gradient">Productor</span>
      </div>
      <div class="logout"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i></div>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <div class="main-content">
      <!-- Título del proyecto -->
      <h1 class="title-gradient">{{ $proyecto->name }}</h1>
      <p class="subtitle">{{ $proyecto->description }}</p>

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
          <div class="stat-value">{{ $proyecto->departamentos->sum(fn($d) => $d->tareas->count()) }}</div>
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
          @foreach($proyecto->departamentos as $departamento)
            @php
              $numMiembrosDep = $departamento->usuarios->count();
              $totalTareasDep = $departamento->tareas->count();
              $tareasFinalizadasDep = $departamento->tareas->where('estado', 'finalizado')->count();
              $progresoDep = $totalTareasDep ? round(($tareasFinalizadasDep / $totalTareasDep) * 100) : 0;
            @endphp

            <div
              class="card main-card hover-lift {{ $loop->index % 7 === 0 ? 'teal' : ($loop->index % 7 === 1 ? 'magenta' : ($loop->index % 7 === 2 ? 'blue' : ($loop->index % 7 === 3 ? 'green' : ($loop->index % 7 === 4 ? 'purple' : ($loop->index % 7 === 5 ? 'orange' : 'pink'))))) }}">
              <div class="main-card-header">
                <div class="main-card-icon">
                  <!-- Icono genérico según el departamento -->
                  @if($departamento->name === 'Desarrollo') <i class="fa-solid fa-code fa-lg"></i>
                  @elseif($departamento->name === 'Arte') <i class="fa-solid fa-palette fa-lg"></i>
                  @elseif($departamento->name === 'Narrativa') <i class="fa-solid fa-scroll fa-lg"></i>
                  @elseif($departamento->name === 'Diseño') <i class="fa-solid fa-gamepad fa-lg"></i>
                  @elseif($departamento->name === 'Audio') <i class="fa-solid fa-music fa-lg"></i>
                  @elseif($departamento->name === 'Marketing') <i class="fa-solid fa-comments-dollar fa-lg"></i>
                  @else <i class="fa-solid fa-circle fa-lg"></i>
                  @endif
                </div>
                <div>
                  <div class="main-card-title">{{ $departamento->name }}</div>
                  <div class="main-card-subtitle">{{ $departamento->description }}</div>
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
                <div class="main-card-tag">Frontend</div>
                <div class="main-card-tag">Backend</div>
                <div class="main-card-tag">DevOps</div>
              </div>
            </div>
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
</body>

</html>
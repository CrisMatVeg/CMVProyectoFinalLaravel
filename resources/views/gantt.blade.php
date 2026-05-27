<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | Gantt — {{ $proyecto->name }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <style>
    .gantt-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: center;
      margin-top: 24px;
      margin-bottom: 28px;
      padding: 16px 20px;
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
    }
    .gantt-filters label {
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .gantt-filters select,
    .gantt-filters input[type="date"] {
      background: var(--usercolor);
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 7px 10px;
      font-size: 13px;
      font-family: var(--font-main);
      min-width: 140px;
    }
    .gantt-filters select:focus,
    .gantt-filters input[type="date"]:focus { outline: none; border-color: var(--accent); }

    .filter-reset {
      margin-left: auto;
      padding: 7px 14px;
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--muted);
      cursor: pointer;
      font-size: 12px;
      transition: all .2s;
      align-self: flex-end;
    }
    .filter-reset:hover { color: var(--white); border-color: var(--accent); }

    @media (max-width: 767px) {
      /* Filtros */
      .gantt-filters {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 14px;
      }
      .gantt-filters select,
      .gantt-filters input[type="date"] {
        min-width: 0;
        width: 100%;
      }
      .gantt-filters > label:nth-child(5),
      .filter-reset {
        grid-column: 1 / 3;
        margin-left: 0;
      }
      .filter-reset {
        align-self: auto;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
      }
    }

    /* Leyenda */
    .gantt-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      margin-bottom: 16px;
      font-size: 11px;
      color: var(--muted);
    }
    .gantt-legend-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .legend-bar {
      width: 24px;
      height: 10px;
      border-radius: 3px;
    }
    .legend-bar.blocked {
      background: #4b5563;
      opacity: .5;
    }
    .legend-diamond {
      width: 10px;
      height: 10px;
      background: #fbbf24;
      transform: rotate(45deg);
      border-radius: 1px;
    }
    .legend-arrow {
      width: 24px;
      height: 2px;
      background: #6b7280;
      position: relative;
    }
    .legend-arrow::after {
      content: '';
      position: absolute;
      right: -4px;
      top: -3px;
      border: 4px solid transparent;
      border-left: 6px solid #6b7280;
    }

    /* Contenedor Gantt */
    .gantt-wrap {
      display: flex;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      overflow: hidden;
      background: var(--cardgrey);
      min-height: 200px;
    }

    /* Panel izquierdo */
    .gantt-left {
      flex-shrink: 0;
      width: 280px;
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
    }
    .gantt-left-header {
      height: 72px;
      display: flex;
      align-items: flex-end;
      padding: 0 16px 12px;
      border-bottom: 1px solid var(--border);
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      font-weight: 600;
      flex-shrink: 0;
    }
    .gantt-task-row {
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 10px 16px;
      border-bottom: 1px solid rgba(55,65,81,.4);
      height: 62px;
      box-sizing: border-box;
      gap: 4px;
    }
    .gantt-task-row.blocked-row { opacity: .5; }
    .gantt-task-name {
      font-size: 12px;
      color: var(--white);
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .gantt-task-badges { display: flex; gap: 5px; align-items: center; }
    .g-badge {
      font-size: 9px;
      padding: 2px 6px;
      border-radius: 4px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .3px;
    }

    /* Panel derecho */
    .gantt-right {
      flex: 1;
      overflow-x: auto;
      overflow-y: hidden;
      display: flex;
      flex-direction: column;
    }
    .gantt-timeline-inner {
      display: flex;
      flex-direction: column;
      position: relative;
    }
    .gantt-months-row {
      display: flex;
      height: 36px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
      position: relative;
    }
    .gantt-month-cell {
      border-right: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      flex-shrink: 0;
    }
    .gantt-weeks-row {
      display: flex;
      height: 36px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
    }
    .gantt-week-cell {
      border-right: 1px solid rgba(55,65,81,.4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      color: var(--muted2);
      flex-shrink: 0;
    }

    /* Cuerpo de barras */
    .gantt-body {
      display: flex;
      flex-direction: column;
      position: relative;
      flex: 1;
    }
    .gantt-bar-row {
      height: 62px;
      position: relative;
      border-bottom: 1px solid rgba(55,65,81,.4);
      display: flex;
      align-items: center;
      flex-shrink: 0;
      overflow: visible;
    }
    .gantt-week-line {
      position: absolute;
      top: 0; bottom: 0;
      width: 1px;
      background: rgba(55,65,81,.3);
      pointer-events: none;
    }

    /* Barra normal */
    .gantt-bar {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      height: 28px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      padding: 0 8px;
      font-size: 10px;
      font-weight: 600;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: default;
      box-sizing: border-box;
      transition: opacity .15s;
      z-index: 2;
    }
    .gantt-bar:hover { opacity: .85; }
    /* Pendiente: rayado diagonal */
    .gantt-bar.pendiente {
      opacity: .75;
      background-image: repeating-linear-gradient(
        135deg, transparent, transparent 4px,
        rgba(0,0,0,.22) 4px, rgba(0,0,0,.22) 8px
      );
    }
    /* En Proceso: borde blanco sutil + glow (el glow se aplica inline desde JS con el color del dept) */
    .gantt-bar.enproceso {
      box-shadow: 0 0 0 1.5px rgba(255,255,255,.28), 0 2px 8px rgba(0,0,0,.4);
    }
    /* Terminada: opacidad baja + franja blanca en el top */
    .gantt-bar.terminada {
      opacity: .55;
      border-top: 3px solid rgba(255,255,255,.55);
    }

    /* Barra bloqueada */
    .gantt-bar.blocked-bar {
      background: #374151 !important;
      opacity: .5;
      background-image: repeating-linear-gradient(
        135deg, transparent, transparent 4px,
        rgba(0,0,0,.3) 4px, rgba(0,0,0,.3) 8px
      ) !important;
    }
    .gantt-bar.blocked-bar::before {
      content: '🔒 ';
      font-size: 9px;
    }

    /* Rombo al final de la barra milestone */
    .ms-end-diamond {
      position: absolute;
      top: 50%;
      width: 14px;
      height: 14px;
      transform: translate(-50%, -50%) rotate(45deg);
      border: 2px solid rgba(255,255,255,.55);
      border-radius: 2px;
      z-index: 4;
      pointer-events: none;
    }

    /* Línea de hoy */
    .gantt-today-line {
      position: absolute;
      top: 0; bottom: 0;
      width: 2px;
      background: var(--accent);
      opacity: .7;
      z-index: 10;
      pointer-events: none;
    }
    .gantt-today-label {
      position: absolute;
      top: 4px;
      font-size: 9px;
      color: var(--accent);
      font-weight: 700;
      transform: translateX(-50%);
      pointer-events: none;
      white-space: nowrap;
    }

    /* SVG de flechas */
    .gantt-arrows-svg {
      position: absolute;
      top: 0; left: 0;
      pointer-events: none;
      overflow: visible;
      z-index: 5;
    }

    .label-inline    { flex-direction: row !important; align-items: center; gap: 6px; }
    .checkbox-accent { accent-color: var(--accent); }
    .legend-bar-teal    { background: #14b8a6; }
    .legend-item-flex   { display: flex; align-items: center; gap: 3px; }
    .legend-bar-ms      { background: #fbbf24; color: #1a1a1a; font-size: 8px; display: flex; align-items: center; padding: 0 4px; }
    .legend-diamond-ms  { background: #fbbf24; flex-shrink: 0; }
    .legend-bar-pending { background: repeating-linear-gradient(135deg,#3b82f6,#3b82f6 4px,rgba(0,0,0,.3) 4px,rgba(0,0,0,.3) 8px); }
    .gantt-mobile-icon  { font-size: 2rem; opacity: .4; }
    .gantt-mobile-text  { font-size: 14px; }
    #gantt-empty { display: none; text-align: center; padding: 60px; color: var(--muted); }
    #gantt-empty i { margin-bottom: 12px; display: block; }

    /* ── Mobile ───────────────────────────────────────── */
    @media (max-width: 767px) {
      #gantt-container,
      .gantt-legend { display: none; }
      .gantt-desktop-only {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 48px 24px;
        border: 1px dashed var(--border);
        border-radius: var(--radius-lg);
        text-align: center;
        color: var(--muted);
      }
    }
    @media (min-width: 768px) {
      .gantt-desktop-only { display: none; }
    }
  </style>
</head>

@php
  $colorMap = [
    'Desarrollo' => ['class' => 'teal',    'hex' => '#14b8a6'],
    'Diseño'     => ['class' => 'magenta', 'hex' => '#ff00ff'],
    'Audio'      => ['class' => 'green',   'hex' => '#4ade80'],
    'Narrativa'  => ['class' => 'purple',  'hex' => '#a855f7'],
    'Marketing'  => ['class' => 'orange',  'hex' => '#fb923c'],
    'Arte'       => ['class' => 'blue',    'hex' => '#3b82f6'],
  ];
@endphp

<body class="teal">
  <aside class="sidebar">
    <div class="logo pixel-logo"><img src="{{ asset('isotipo.png') }}" alt="">PIXEL</div>
    <nav class="menu">
      <span class="menu-section">Proyecto</span>
      <a href="{{ route('proyecto', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
        <span>Proyecto</span>
      </a>
      <div class="menu-item hover-lift active" data-mobile-hide="true">
        <span class="menu-icon"><i class="fa-solid fa-chart-gantt"></i></span>
        <span>Gantt</span>
      </div>
      <a href="{{ route('proyecto.calendario', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
        <span>Calendario</span>
      </a>
      <a href="{{ route('proyecto.foro', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-comments"></i></span>
        <span>Foro</span>
      </a>
      <a href="{{ route('proyecto.archivos', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
        <span>Archivos</span>
      </a>
      <a href="{{ route('proyecto.predefinicion', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
        <span>Datos predefinidos</span>
      </a>
      @if($proyecto->created_by === auth()->id())
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
          <span class="title-gradient">{{ $proyecto->created_by === auth()->id() ? 'Owner' : 'Miembro' }}</span>
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
      <h1 class="title-gradient">
        <i class="fa-solid fa-chart-gantt btn-icon-mr-lg"></i>Diagrama Gantt
      </h1>
      <p class="subtitle">{{ $proyecto->name }} · {{ $proyecto->tareas->count() }} tareas en total</p>

      <!-- FILTROS -->
      <div class="gantt-filters">
        <label>Estado
          <select id="f-estado">
            <option value="">Todos</option>
            @foreach($estados as $e)
              <option value="{{ $e->name }}">{{ $e->name }}</option>
            @endforeach
          </select>
        </label>
        <label>Departamento
          <select id="f-tipo">
            <option value="">Todos</option>
            @foreach($tipos as $t)
              <option value="{{ $t->name }}">{{ $t->name }}</option>
            @endforeach
          </select>
        </label>
        <label>Desde
          <input type="date" id="f-desde">
        </label>
        <label>Hasta
          <input type="date" id="f-hasta">
        </label>
        <label class="label-inline">
          <input type="checkbox" id="f-milestones" class="checkbox-accent">
          <span>Solo milestones</span>
        </label>
        <button class="filter-reset" onclick="resetFilters()">
          <i class="fa-solid fa-rotate-left"></i> Resetear
        </button>
      </div>

      <!-- LEYENDA -->
      <div class="gantt-legend">
        <div class="gantt-legend-item">
          <div class="legend-bar legend-bar-teal"></div>
          <span>Tarea normal</span>
        </div>
        <div class="gantt-legend-item">
          <div class="legend-bar blocked"></div>
          <span>Bloqueada</span>
        </div>
        <div class="gantt-legend-item">
          <div class="legend-item-flex">
            <div class="legend-bar legend-bar-ms">◆</div>
            <div class="legend-diamond legend-diamond-ms"></div>
          </div>
          <span>Milestone</span>
        </div>
        <div class="gantt-legend-item">
          <div class="legend-bar legend-bar-pending"></div>
          <span>Pendiente (rayado)</span>
        </div>
        <div class="gantt-legend-item">
          <div class="legend-arrow"></div>
          <span>Dependencia</span>
        </div>
      </div>

      <!-- Aviso solo mobile -->
      <div class="gantt-desktop-only">
        <i class="fa-solid fa-desktop gantt-mobile-icon"></i>
        <span class="gantt-mobile-text">El diagrama de Gantt solo está disponible en escritorio.</span>
      </div>

      <!-- GANTT -->
      <div id="gantt-container"></div>
      <p id="gantt-empty">
        <i class="fa-solid fa-magnifying-glass fa-2x"></i>
        Ninguna tarea coincide con los filtros.
      </p>
    </div>

    <footer class="footer">
      <p>© 2025 PIXEL. Todos los derechos reservados.</p>
    </footer>
  </main>

  <script>
    const ALL_TASKS = @json($tareasJson);

    const DEPT_COLORS = {
      'Desarrollo': '#14b8a6',
      'Diseño':     '#ff00ff',
      'Audio':      '#4ade80',
      'Narrativa':  '#a855f7',
      'Marketing':  '#fb923c',
      'Arte':       '#3b82f6',
    };

    const ROW_H  = 62;
    const DAY_PX = 22;
    const MONTHS_ES = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

    // ── Helpers de fecha ──────────────────────────────────────────────────
    function parseDate(str) {
      const [y, m, d] = str.split('-').map(Number);
      return new Date(y, m - 1, d);
    }
    function daysBetween(a, b) {
      return Math.round((b - a) / 86400000);
    }
    function addDays(date, n) {
      const d = new Date(date);
      d.setDate(d.getDate() + n);
      return d;
    }
    function formatDate(date) {
      return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
    }

    // ── Filtros ───────────────────────────────────────────────────────────
    function getFilters() {
      return {
        estado:      document.getElementById('f-estado').value,
        tipo:        document.getElementById('f-tipo').value,
        desde:       document.getElementById('f-desde').value,
        hasta:       document.getElementById('f-hasta').value,
        milestones:  document.getElementById('f-milestones').checked,
      };
    }

    function applyFilters(tasks) {
      const f = getFilters();
      return tasks.filter(t => {
        if (!t.start || !t.end) return false;
        if (f.estado     && t.estado !== f.estado) return false;
        if (f.tipo       && t.tipo   !== f.tipo)   return false;
        if (f.desde      && t.end    <  f.desde)   return false;
        if (f.hasta      && t.start  >  f.hasta)   return false;
        if (f.milestones && !t.is_milestone)        return false;
        return true;
      });
    }

    function resetFilters() {
      ['f-estado','f-tipo','f-desde','f-hasta'].forEach(id => {
        document.getElementById(id).value = '';
      });
      document.getElementById('f-milestones').checked = false;
      render();
    }

    // ── Render ────────────────────────────────────────────────────────────
    function render() {
      const container = document.getElementById('gantt-container');
      const emptyMsg  = document.getElementById('gantt-empty');
      const tasks     = applyFilters(ALL_TASKS);

      if (tasks.length === 0) {
        container.innerHTML = '';
        emptyMsg.style.display = 'block';
        return;
      }
      emptyMsg.style.display = 'none';

      // Rango de fechas
      const allStarts = tasks.map(t => parseDate(t.start));
      const allEnds   = tasks.map(t => parseDate(t.end));
      const minDate   = new Date(Math.min(...allStarts));
      const maxDate   = new Date(Math.max(...allEnds));
      const rStart    = addDays(minDate, -4);
      const rEnd      = addDays(maxDate, 4);
      const totalDays = daysBetween(rStart, rEnd) + 1;
      const timelineW = totalDays * DAY_PX;

      function dayOffset(dateStr) {
        return daysBetween(rStart, parseDate(dateStr));
      }
      function barLeftPx(task)  { return dayOffset(task.start) * DAY_PX; }
      function barWidthPx(task) { return (daysBetween(parseDate(task.start), parseDate(task.end)) + 1) * DAY_PX; }

      // ── Meses ──────────────────────────────────────────────────────────
      let monthsHTML = '';
      let cur = new Date(rStart.getFullYear(), rStart.getMonth(), 1);
      while (cur <= rEnd) {
        const mStart = cur < rStart ? rStart : cur;
        const nextM  = new Date(cur.getFullYear(), cur.getMonth() + 1, 1);
        const mEnd   = nextM > rEnd ? rEnd : new Date(nextM - 1);
        const days   = daysBetween(mStart, mEnd) + 1;
        monthsHTML  += `<div class="gantt-month-cell" style="width:${days * DAY_PX}px;">${MONTHS_ES[cur.getMonth()]} ${cur.getFullYear()}</div>`;
        cur = nextM;
      }

      // ── Semanas ────────────────────────────────────────────────────────
      let weeksHTML  = '';
      const weekLinesPx = [];
      let wCur = new Date(rStart);
      const dow = wCur.getDay();
      wCur.setDate(wCur.getDate() - (dow === 0 ? 6 : dow - 1));
      while (wCur <= rEnd) {
        const lx = daysBetween(rStart, wCur) * DAY_PX;
        if (lx >= 0 && lx <= timelineW) {
          weeksHTML += `<div class="gantt-week-cell" style="width:${7 * DAY_PX}px;">${formatDate(wCur)}</div>`;
          weekLinesPx.push(lx);
        }
        wCur.setDate(wCur.getDate() + 7);
      }

      // ── Línea de hoy ───────────────────────────────────────────────────
      const today    = new Date(); today.setHours(0,0,0,0);
      const todayPx  = daysBetween(rStart, today) * DAY_PX;
      const showToday = todayPx >= 0 && todayPx <= timelineW;

      const wLinesHTML = weekLinesPx.map(lx =>
        `<div class="gantt-week-line" style="left:${lx}px;"></div>`
      ).join('');

      const todayLineHTML = showToday
        ? `<div class="gantt-today-line" style="left:${todayPx}px;"></div>`
        : '';
      const todayLabelHTML = showToday
        ? `<div class="gantt-today-label" style="left:${todayPx}px;">HOY</div>`
        : '';

      // ── Índice taskId → índice en lista visible ─────────────────────────
      const taskIndex = {};
      tasks.forEach((t, i) => { taskIndex[t.id] = i; });

      // ── Construir filas ────────────────────────────────────────────────
      let leftRows = '';
      let barRows  = '';

      tasks.forEach((task, i) => {
        const color   = DEPT_COLORS[task.tipo] || '#9ca3af';
        const blocked = task.blocked;
        const stClass = blocked          ? 'blocked-bar'
                      : task.estado === 'Pendiente'  ? 'pendiente'
                      : task.estado === 'En Proceso' ? 'enproceso'
                      : task.estado === 'Terminada'  ? 'terminada' : '';

        // Prefijo de estado en la barra
        const stPrefix = blocked          ? '🔒 '
                       : task.estado === 'Terminada'  ? '✓ '
                       : task.estado === 'En Proceso' ? '▶ '
                       : '○ ';

        const msPrefix  = task.is_milestone ? '◆ ' : '';
        const lockIcon  = blocked ? '<i class="fa-solid fa-lock" style="margin-right:4px;font-size:9px;"></i>' : '';
        const rowClass  = blocked ? 'blocked-row' : '';

        // Glow inline solo para "En Proceso" (usa el color del dept)
        const glowStyle = (!blocked && task.estado === 'En Proceso')
          ? `box-shadow:0 0 0 1.5px rgba(255,255,255,.28),0 0 10px ${color}66,0 2px 8px rgba(0,0,0,.4);`
          : '';

        // Panel izquierdo
        leftRows += `
          <div class="gantt-task-row ${rowClass}">
            <div class="gantt-task-name" title="${task.title}">${lockIcon}${msPrefix}${task.title}</div>
            <div class="gantt-task-badges">
              <span class="g-badge" style="background:${color}22;color:${color};">${task.tipo}</span>
              <span class="g-badge" style="background:rgba(156,163,175,.15);color:var(--muted);">${blocked ? '🔒 Bloqueada' : task.estado}</span>
            </div>
          </div>`;

        // Barra / Milestone
        const lx = barLeftPx(task);
        const wx = barWidthPx(task);
        const tooltip = `${task.title} | ${task.start} → ${task.end} | ${task.hours}h`;
        const barColor = blocked ? '#374151' : color;
        const label    = stPrefix + task.title;

        let barHTML;
        if (task.is_milestone) {
          const diamondLeft = lx + wx;
          barHTML = `
            <div class="gantt-bar ${stClass}"
                 style="left:${lx}px;width:${wx}px;background-color:${barColor};${glowStyle}"
                 title="${tooltip}">${label}</div>
            <div class="ms-end-diamond"
                 style="left:${diamondLeft}px;background:#fbbf24;"></div>`;
        } else {
          barHTML = `<div class="gantt-bar ${stClass}"
                          style="left:${lx}px;width:${wx}px;background-color:${barColor};${glowStyle}"
                          title="${tooltip}">${label}</div>`;
        }

        barRows += `
          <div class="gantt-bar-row" style="width:${timelineW}px;" data-task-id="${task.id}" data-row="${i}">
            ${wLinesHTML}
            ${todayLineHTML}
            ${barHTML}
          </div>`;
      });

      // ── Flechas SVG ────────────────────────────────────────────────────
      let arrowPaths = '';
      tasks.forEach((task, ti) => {
        if (!task.depends_on || task.depends_on.length === 0) return;

        const ty  = ti * ROW_H + ROW_H / 2;
        // Tanto para milestone como barra normal: left edge de la barra
        const tx  = barLeftPx(task);

        task.depends_on.forEach(depId => {
          const di = taskIndex[depId];
          if (di === undefined) return; // dep filtrada

          const depTask = tasks[di];
          const dy  = di * ROW_H + ROW_H / 2;
          // Right edge de la barra (igual para milestone y tarea normal)
          const dx  = barLeftPx(depTask) + barWidthPx(depTask);

          // Enrutado en L: right → vertical → left
          const elbowX = Math.max(dx + 12, tx - 12);
          const arrowColor = depTask.blocked ? '#4b5563' : '#6b7280';

          arrowPaths += `<path
            d="M${dx},${dy} H${elbowX} V${ty} H${tx}"
            stroke="${arrowColor}" stroke-width="1.5" fill="none"
            marker-end="url(#arrowhead)"/>`;
        });
      });

      const svgH = tasks.length * ROW_H;
      const arrowsSVG = `
        <svg class="gantt-arrows-svg" width="${timelineW}" height="${svgH}">
          <defs>
            <marker id="arrowhead" markerWidth="7" markerHeight="5"
                    refX="7" refY="2.5" orient="auto">
              <polygon points="0 0, 7 2.5, 0 5" fill="#6b7280"/>
            </marker>
          </defs>
          ${arrowPaths}
        </svg>`;

      // ── Ensamblar HTML ─────────────────────────────────────────────────
      container.innerHTML = `
        <div class="gantt-wrap">
          <div class="gantt-left">
            <div class="gantt-left-header">Tarea / Departamento</div>
            ${leftRows}
          </div>
          <div class="gantt-right">
            <div class="gantt-timeline-inner" style="width:${timelineW}px;">
              <div class="gantt-months-row">
                ${monthsHTML}
                ${todayLabelHTML}
                ${showToday ? `<div class="gantt-today-line" style="left:${todayPx}px;"></div>` : ''}
              </div>
              <div class="gantt-weeks-row">${weeksHTML}</div>
              <div class="gantt-body" style="height:${svgH}px;">
                ${barRows}
                ${arrowsSVG}
              </div>
            </div>
          </div>
        </div>`;

      // Scroll hacia hoy (en mobile usa gantt-wrap como scroll container)
      const rp = container.querySelector('.gantt-right');
      const gw = container.querySelector('.gantt-wrap');
      const scrollTarget = (window.innerWidth < 768 && gw) ? gw : rp;
      if (scrollTarget && showToday) scrollTarget.scrollLeft = Math.max(0, todayPx - 110);
    }

    // ── Init ──────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
      render();
      ['f-estado','f-tipo','f-desde','f-hasta'].forEach(id =>
        document.getElementById(id).addEventListener('change', render)
      );
      document.getElementById('f-milestones').addEventListener('change', render);
    });
  </script>
</body>
</html>

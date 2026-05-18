<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | Calendario — {{ $proyecto->name }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <style>
    /* ── Cabecera del mes ─────────────────────────────────── */
    .cal-header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 24px;
      margin-bottom: 28px;
    }
    .cal-nav-btn {
      width: 40px; height: 40px;
      border-radius: 50%;
      border: 1px solid var(--border);
      background: var(--cardgrey);
      color: var(--white);
      cursor: pointer;
      font-size: 16px;
      display: flex; align-items: center; justify-content: center;
      transition: all .2s;
      flex-shrink: 0;
    }
    .cal-nav-btn:hover { border-color: var(--accent); color: var(--accent); }
    .cal-month-label {
      font-family: var(--font-title);
      font-size: 28px;
      font-weight: 700;
      min-width: 260px;
      text-align: center;
      letter-spacing: .5px;
    }

    /* ── Filtros ──────────────────────────────────────────── */
    .cal-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: center;
      margin-bottom: 24px;
      padding: 14px 20px;
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
    }
    .cal-filters label {
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .cal-filters select {
      background: var(--usercolor);
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 6px 10px;
      font-size: 13px;
      font-family: var(--font-main);
      min-width: 140px;
    }
    .cal-filters select:focus { outline: none; border-color: var(--accent); }
    .cal-filter-reset {
      margin-left: auto;
      padding: 6px 14px;
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--muted);
      cursor: pointer;
      font-size: 12px;
      transition: all .2s;
      align-self: flex-end;
    }
    .cal-filter-reset:hover { color: var(--white); border-color: var(--accent); }

    /* ── Grid del calendario ──────────────────────────────── */
    .cal-grid-wrap {
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      overflow: hidden;
      background: var(--cardgrey);
      transition: opacity .18s ease;
    }
    .cal-grid-wrap.fading { opacity: 0; }

    .cal-weekdays {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      border-bottom: 1px solid var(--border);
    }
    .cal-weekday {
      padding: 10px 0;
      text-align: center;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .6px;
      color: var(--muted);
    }
    .cal-weekday.weekend { color: var(--muted2); }

    .cal-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
    }
    .cal-day {
      min-height: 110px;
      padding: 8px 6px 6px;
      border-right: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      gap: 3px;
      position: relative;
      transition: background .15s;
    }
    .cal-day:nth-child(7n) { border-right: none; }
    .cal-day.outside-month { background: rgba(0,0,0,.15); }
    .cal-day.today {
      background: rgba(20,184,166,.06);
      border-color: var(--accent);
    }

    /* Número del día */
    .cal-day-number {
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      line-height: 1;
      margin-bottom: 2px;
      align-self: flex-end;
      border-radius: 50%;
      width: 22px; height: 22px;
      display: flex; align-items: center; justify-content: center;
    }
    .cal-day.today .cal-day-number {
      color: var(--accent);
      background: var(--accentwop1);
      font-size: 11px;
    }
    .cal-day.outside-month .cal-day-number { color: var(--muted2); opacity: .4; }
    .cal-day-number.has-tasks {
      cursor: pointer;
      transition: color .15s, background .15s;
    }
    .cal-day-number.has-tasks:hover {
      color: var(--accent);
      background: var(--accentwop1);
    }

    /* ── Chips de tarea ───────────────────────────────────── */
    .cal-chip {
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 2px 5px;
      border-radius: 4px;
      font-size: 10px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
      overflow: hidden;
      color: #fff;
      transition: opacity .15s, transform .1s;
      line-height: 1.4;
      flex-shrink: 0;
    }
    .cal-chip:hover { opacity: .82; transform: scale(1.02); }
    .cal-chip-dot {
      width: 5px; height: 5px;
      border-radius: 50%;
      background: rgba(255,255,255,.7);
      flex-shrink: 0;
    }
    .cal-chip-title {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    /* Botón "+N más" */
    .cal-overflow {
      font-size: 10px;
      font-weight: 600;
      color: var(--accent);
      cursor: pointer;
      padding: 2px 6px;
      border-radius: 4px;
      background: var(--accentwop1);
      border: 1px solid var(--accentwop2);
      transition: background .15s, color .15s;
      line-height: 1.4;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .cal-overflow:hover { background: var(--accentwop2); }

    /* ── Leyenda ──────────────────────────────────────────── */
    .cal-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      margin-bottom: 20px;
      font-size: 11px;
      color: var(--muted);
    }
    .cal-legend-item { display: flex; align-items: center; gap: 6px; }
    .cal-legend-dot { width: 10px; height: 10px; border-radius: 3px; }

    /* ── Overlay compartido ───────────────────────────────── */
    .cal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.65);
      z-index: 100;
      align-items: center;
      justify-content: center;
    }
    .cal-overlay.open { display: flex; }

    /* ── Modal base ───────────────────────────────────────── */
    .cal-modal-box {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      position: relative;
      box-shadow: 0 20px 60px rgba(0,0,0,.6);
      animation: modal-in .2s ease;
    }
    @keyframes modal-in {
      from { opacity: 0; transform: translateY(12px) scale(.97); }
      to   { opacity: 1; transform: translateY(0)   scale(1); }
    }
    .cal-modal-close {
      position: absolute;
      top: 14px; right: 14px;
      background: none;
      border: none;
      color: var(--muted);
      font-size: 18px;
      cursor: pointer;
      transition: color .15s;
      line-height: 1;
    }
    .cal-modal-close:hover { color: var(--white); }

    /* ── Modal de DÍA ────────────────────────────────────── */
    #day-modal {
      width: 90%;
      max-width: 540px;
      padding: 24px 28px 28px;
    }
    .day-modal-header {
      margin-bottom: 20px;
      padding-right: 28px;
    }
    .day-modal-date {
      font-family: var(--font-title);
      font-size: 22px;
      font-weight: 700;
      color: var(--white);
      line-height: 1.2;
    }
    .day-modal-count {
      font-size: 12px;
      color: var(--muted);
      margin-top: 4px;
    }
    .day-task-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      max-height: 420px;
      overflow-y: auto;
      padding-right: 2px;
    }
    .day-task-list::-webkit-scrollbar { width: 4px; }
    .day-task-list::-webkit-scrollbar-track { background: transparent; }
    .day-task-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

    .day-task-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid var(--border);
      cursor: pointer;
      transition: background .15s, border-color .15s;
      background: rgba(255,255,255,.02);
    }
    .day-task-item:hover {
      background: rgba(255,255,255,.05);
      border-color: var(--accent);
    }
    .day-task-item-dot {
      width: 9px; height: 9px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .day-task-item-info {
      flex: 1;
      min-width: 0;
    }
    .day-task-item-title {
      font-size: 13px;
      font-weight: 600;
      color: var(--white);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .day-task-item-dates {
      font-size: 11px;
      color: var(--muted);
      margin-top: 2px;
    }
    .day-task-item-badges {
      display: flex;
      gap: 5px;
      flex-shrink: 0;
    }
    .day-task-item-badge {
      font-size: 9px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 5px;
      text-transform: uppercase;
      letter-spacing: .3px;
    }
    .day-task-item-arrow {
      color: var(--muted2);
      font-size: 11px;
      flex-shrink: 0;
      transition: color .15s;
    }
    .day-task-item:hover .day-task-item-arrow { color: var(--accent); }

    /* ── Modal de TAREA (detalle) ────────────────────────── */
    #task-modal {
      width: 90%;
      max-width: 480px;
      padding: 28px 32px;
    }
    .task-modal-back {
      display: none;
      align-items: center;
      gap: 6px;
      background: none;
      border: none;
      color: var(--muted);
      font-size: 12px;
      font-family: var(--font-main);
      cursor: pointer;
      padding: 0;
      margin-bottom: 16px;
      transition: color .15s;
    }
    .task-modal-back:hover { color: var(--accent); }
    .task-modal-back i { font-size: 11px; }

    .task-modal-color-bar {
      height: 4px;
      border-radius: 2px;
      margin-bottom: 16px;
    }
    .task-modal-title {
      font-family: var(--font-title);
      font-size: 20px;
      font-weight: 700;
      color: var(--white);
      margin-bottom: 14px;
      padding-right: 28px;
      line-height: 1.3;
    }
    .task-modal-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 18px;
    }
    .task-modal-badge {
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 6px;
      text-transform: uppercase;
      letter-spacing: .4px;
    }
    .task-modal-meta {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 18px;
    }
    .task-modal-meta-row {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      color: var(--muted);
    }
    .task-modal-meta-row i {
      width: 16px;
      text-align: center;
      color: var(--accent);
      flex-shrink: 0;
    }
    .task-modal-desc {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.6;
      border-top: 1px solid var(--border);
      padding-top: 14px;
    }
  </style>
</head>

@php
  $colorMap = [
    'Desarrollo' => '#14b8a6',
    'Diseño'     => '#ff00ff',
    'Audio'      => '#3b82f6',
    'Narrativa'  => '#4ade80',
    'Marketing'  => '#a855f7',
    'Arte'       => '#fb923c',
  ];
  $estadoColors = [
    'Pendiente'  => '#6b7280',
    'En Proceso' => '#f59e0b',
    'Terminada'  => '#10b981',
  ];
@endphp

<body class="teal">
  <aside class="sidebar">
    <div class="logo pixel-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>
    <nav class="menu">
      <span class="menu-section">Proyecto</span>
      <a href="{{ route('proyecto', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
        <span>Proyecto</span>
      </a>
      <a href="{{ route('proyecto.gantt', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-chart-gantt"></i></span>
        <span>Gantt</span>
      </a>
      <div class="menu-item hover-lift active">
        <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
        <span>Calendario</span>
      </div>
      <a href="{{ route('proyecto.foro', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-comments"></i></span>
        <span>Foro</span>
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
        <i class="fa-solid fa-calendar-days" style="margin-right:10px;"></i>Calendario de Tareas
      </h1>
      <p class="subtitle">{{ $proyecto->name }} · {{ $proyecto->tareas->count() }} tareas en total</p>

      <!-- FILTROS -->
      <div class="cal-filters" style="margin-top:24px;">
        <label>Departamento
          <select id="f-tipo">
            <option value="">Todos</option>
            @foreach($tipos as $t)
              <option value="{{ $t->name }}">{{ $t->name }}</option>
            @endforeach
          </select>
        </label>
        <label>Estado
          <select id="f-estado">
            <option value="">Todos</option>
            @foreach($estados as $e)
              <option value="{{ $e->name }}">{{ $e->name }}</option>
            @endforeach
          </select>
        </label>
        <button class="cal-filter-reset" onclick="resetFilters()">
          <i class="fa-solid fa-rotate-left"></i> Resetear
        </button>
      </div>

      <!-- LEYENDA -->
      <div class="cal-legend">
        @foreach($colorMap as $dept => $color)
          <div class="cal-legend-item">
            <div class="cal-legend-dot" style="background:{{ $color }};"></div>
            <span>{{ $dept }}</span>
          </div>
        @endforeach
      </div>

      <!-- CABECERA MES -->
      <div class="cal-header">
        <button class="cal-nav-btn" onclick="changeMonth(-1)" title="Mes anterior">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        <div class="cal-month-label title-gradient" id="cal-month-label">—</div>
        <button class="cal-nav-btn" onclick="changeMonth(1)" title="Mes siguiente">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>

      <!-- GRID -->
      <div class="cal-grid-wrap" id="cal-grid-wrap">
        <div class="cal-weekdays">
          <div class="cal-weekday">Lun</div>
          <div class="cal-weekday">Mar</div>
          <div class="cal-weekday">Mié</div>
          <div class="cal-weekday">Jue</div>
          <div class="cal-weekday">Vie</div>
          <div class="cal-weekday weekend">Sáb</div>
          <div class="cal-weekday weekend">Dom</div>
        </div>
        <div class="cal-days" id="cal-days"></div>
      </div>

    </div>

    <footer class="footer">
      <p>© 2025 PIXEL. Todos los derechos reservados.</p>
    </footer>
  </main>

  <!-- ══ MODAL DE DÍA ══════════════════════════════════════ -->
  <div class="cal-overlay" id="day-overlay">
    <div class="cal-modal-box" id="day-modal">
      <button class="cal-modal-close" onclick="closeDayModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <div class="day-modal-header">
        <div class="day-modal-date" id="day-modal-date">—</div>
        <div class="day-modal-count" id="day-modal-count"></div>
      </div>
      <div class="day-task-list" id="day-task-list"></div>
    </div>
  </div>

  <!-- ══ MODAL DE TAREA (detalle) ══════════════════════════ -->
  <div class="cal-overlay" id="task-overlay">
    <div class="cal-modal-box" id="task-modal">
      <button class="cal-modal-close" onclick="closeTaskModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <button class="task-modal-back" id="task-back-btn" onclick="goBackToDay()">
        <i class="fa-solid fa-arrow-left"></i> Volver al día
      </button>
      <div class="task-modal-color-bar" id="task-color-bar"></div>
      <div class="task-modal-title" id="task-title">—</div>
      <div class="task-modal-badges" id="task-badges"></div>
      <div class="task-modal-meta" id="task-meta"></div>
      <div class="task-modal-desc" id="task-desc"></div>
    </div>
  </div>

  <script>
    const ALL_TASKS = @json($tareasJson);

    const DEPT_COLORS = @json($colorMap);
    const ESTADO_COLORS = {
      'Pendiente':  '#6b7280',
      'En Proceso': '#f59e0b',
      'Terminada':  '#10b981',
    };
    const MONTHS_ES = [
      'Enero','Febrero','Marzo','Abril','Mayo','Junio',
      'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
    ];

    const today = new Date();
    today.setHours(0,0,0,0);
    let curYear  = today.getFullYear();
    let curMonth = today.getMonth();

    // dayMap global para que openDayModal pueda acceder a las tareas del día
    let globalDayMap = {};
    // Flag: el modal de tarea fue abierto desde el modal de día
    let openedFromDay = false;

    // ── Helpers ──────────────────────────────────────────────
    function parseDate(str) {
      if (!str) return null;
      const [y,m,d] = str.split('-').map(Number);
      return new Date(y, m-1, d);
    }
    function toYMD(date) {
      return `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
    }
    function formatDateHuman(str) {
      if (!str) return '—';
      return parseDate(str).toLocaleDateString('es-ES', { day:'2-digit', month:'short', year:'numeric' });
    }

    // ── Filtros ──────────────────────────────────────────────
    function getFilters() {
      return {
        tipo:   document.getElementById('f-tipo').value,
        estado: document.getElementById('f-estado').value,
      };
    }
    function filteredTasks() {
      const f = getFilters();
      return ALL_TASKS.filter(t => {
        if (!t.start || !t.end) return false;
        if (f.tipo   && t.tipo   !== f.tipo)   return false;
        if (f.estado && t.estado !== f.estado) return false;
        return true;
      });
    }
    function resetFilters() {
      document.getElementById('f-tipo').value   = '';
      document.getElementById('f-estado').value = '';
      renderCalendar();
    }

    // ── Navegación de mes ────────────────────────────────────
    function changeMonth(delta) {
      const wrap = document.getElementById('cal-grid-wrap');
      wrap.classList.add('fading');
      setTimeout(() => {
        curMonth += delta;
        if (curMonth > 11) { curMonth = 0; curYear++; }
        if (curMonth < 0)  { curMonth = 11; curYear--; }
        renderCalendar();
        wrap.classList.remove('fading');
      }, 180);
    }

    // ── Render del calendario ────────────────────────────────
    function renderCalendar() {
      document.getElementById('cal-month-label').textContent =
        `${MONTHS_ES[curMonth]} ${curYear}`;

      const tasks    = filteredTasks();
      const firstDay = new Date(curYear, curMonth, 1);
      const lastDay  = new Date(curYear, curMonth+1, 0);

      // Construir dayMap
      globalDayMap = {};
      tasks.forEach(t => {
        const e = parseDate(t.end);
        if (!e) return;
        if (e < firstDay || e > lastDay) return;
        const key = toYMD(e);
        if (!globalDayMap[key]) globalDayMap[key] = [];
        globalDayMap[key].push(t);
      });

      // Offset del primer día (Lun=0 … Dom=6)
      let startDow = firstDay.getDay();
      startDow = (startDow === 0) ? 6 : startDow - 1;

      const daysInMonth = lastDay.getDate();
      const prevDaysInM = new Date(curYear, curMonth, 0).getDate();
      const MAX_CHIPS   = 3;

      let cells = '';

      // Días del mes anterior (relleno)
      for (let i = startDow - 1; i >= 0; i--) {
        cells += `<div class="cal-day outside-month">
          <div class="cal-day-number">${prevDaysInM - i}</div>
        </div>`;
      }

      // Días del mes actual
      for (let d = 1; d <= daysInMonth; d++) {
        const dateObj  = new Date(curYear, curMonth, d);
        const key      = toYMD(dateObj);
        const isToday  = (key === toYMD(today));
        const dayTasks = globalDayMap[key] || [];
        const total    = dayTasks.length;

        // Número del día — clicable si tiene tareas
        const numClass = total > 0 ? 'cal-day-number has-tasks' : 'cal-day-number';
        const numClick = total > 0
          ? `onclick="openDayModal('${key}', '${d} de ${MONTHS_ES[curMonth]} ${curYear}')"`
          : '';
        const numTitle = total > 0 ? `title="Ver ${total} tarea${total !== 1 ? 's' : ''}"` : '';

        let chipsHTML = '';

        dayTasks.slice(0, MAX_CHIPS).forEach(t => {
          const color = DEPT_COLORS[t.tipo] || '#9ca3af';
          const idx   = ALL_TASKS.findIndex(x => x.id === t.id);
          chipsHTML += `
            <div class="cal-chip" style="background:${color}cc;"
                 onclick="openTaskModal(${idx}, false)"
                 title="${t.title.replace(/"/g,'&quot;')}">
              <div class="cal-chip-dot"></div>
              <span class="cal-chip-title">${t.title}</span>
            </div>`;
        });

        if (total > MAX_CHIPS) {
          const hidden = total - MAX_CHIPS;
          chipsHTML += `
            <div class="cal-overflow"
                 onclick="openDayModal('${key}', '${d} de ${MONTHS_ES[curMonth]} ${curYear}')"
                 title="Ver las ${total} tareas de este día">
              <i class="fa-solid fa-plus" style="font-size:8px;"></i>
              ${hidden} más
            </div>`;
        }

        cells += `
          <div class="cal-day${isToday ? ' today' : ''}">
            <div class="${numClass}" ${numClick} ${numTitle}>${d}</div>
            ${chipsHTML}
          </div>`;
      }

      // Relleno del final
      const totalCells = startDow + daysInMonth;
      const trailing   = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
      for (let d = 1; d <= trailing; d++) {
        cells += `<div class="cal-day outside-month">
          <div class="cal-day-number">${d}</div>
        </div>`;
      }

      document.getElementById('cal-days').innerHTML = cells;
    }

    // ── Modal de DÍA ─────────────────────────────────────────
    function openDayModal(key, label) {
      const tasks = globalDayMap[key] || [];

      document.getElementById('day-modal-date').textContent = label;
      document.getElementById('day-modal-count').textContent =
        `${tasks.length} tarea${tasks.length !== 1 ? 's' : ''} activa${tasks.length !== 1 ? 's' : ''}`;

      let listHTML = '';
      tasks.forEach(t => {
        const color       = DEPT_COLORS[t.tipo]    || '#9ca3af';
        const estadoColor = ESTADO_COLORS[t.estado] || '#6b7280';
        const idx         = ALL_TASKS.findIndex(x => x.id === t.id);
        listHTML += `
          <div class="day-task-item" onclick="openTaskFromDay(${idx})">
            <div class="day-task-item-dot" style="background:${color};"></div>
            <div class="day-task-item-info">
              <div class="day-task-item-title">${t.title}</div>
              <div class="day-task-item-dates">
                ${formatDateHuman(t.start)} → ${formatDateHuman(t.end)} · ${t.hours}h
              </div>
            </div>
            <div class="day-task-item-badges">
              <span class="day-task-item-badge"
                    style="background:${color}22;color:${color};">${t.tipo}</span>
              <span class="day-task-item-badge"
                    style="background:${estadoColor}22;color:${estadoColor};">${t.estado}</span>
            </div>
            <i class="fa-solid fa-chevron-right day-task-item-arrow"></i>
          </div>`;
      });

      document.getElementById('day-task-list').innerHTML = listHTML;
      document.getElementById('day-overlay').classList.add('open');
    }

    function closeDayModal() {
      document.getElementById('day-overlay').classList.remove('open');
    }
    // ── Modal de TAREA (detalle) ─────────────────────────────
    function openTaskFromDay(taskIdx) {
      // Cierra el day modal y abre el task modal con flag de retorno
      document.getElementById('day-overlay').classList.remove('open');
      openTaskModal(taskIdx, true);
    }

    function openTaskModal(taskIdx, fromDay) {
      const t = ALL_TASKS[taskIdx];
      if (!t) return;

      openedFromDay = !!fromDay;
      document.getElementById('task-back-btn').style.display = fromDay ? 'flex' : 'none';

      const deptColor   = DEPT_COLORS[t.tipo]    || '#9ca3af';
      const estadoColor = ESTADO_COLORS[t.estado] || '#6b7280';

      document.getElementById('task-color-bar').style.background = deptColor;
      document.getElementById('task-title').textContent = t.title;

      const msTag = t.is_milestone
        ? `<span class="task-modal-badge" style="background:rgba(251,191,36,.15);color:#fbbf24;">◆ Milestone</span>`
        : '';

      document.getElementById('task-badges').innerHTML = `
        <span class="task-modal-badge" style="background:${deptColor}22;color:${deptColor};">
          ${t.tipo}
        </span>
        <span class="task-modal-badge" style="background:${estadoColor}22;color:${estadoColor};">
          ${t.estado}
        </span>
        ${msTag}`;

      document.getElementById('task-meta').innerHTML = `
        <div class="task-modal-meta-row">
          <i class="fa-solid fa-calendar-day"></i>
          <span>Inicio: <strong style="color:var(--white);">${formatDateHuman(t.start)}</strong></span>
        </div>
        <div class="task-modal-meta-row">
          <i class="fa-solid fa-calendar-check"></i>
          <span>Fin: <strong style="color:var(--white);">${formatDateHuman(t.end)}</strong></span>
        </div>
        <div class="task-modal-meta-row">
          <i class="fa-solid fa-clock"></i>
          <span>Horas estimadas: <strong style="color:var(--white);">${t.hours}h</strong></span>
        </div>`;

      document.getElementById('task-desc').textContent = t.description || 'Sin descripción.';
      document.getElementById('task-overlay').classList.add('open');
    }

    function closeTaskModal() {
      document.getElementById('task-overlay').classList.remove('open');
      openedFromDay = false;
    }

    function goBackToDay() {
      // Cierra el task modal y reabre el day modal
      document.getElementById('task-overlay').classList.remove('open');
      openedFromDay = false;
      document.getElementById('day-overlay').classList.add('open');
    }



    // ── Teclado ──────────────────────────────────────────────
    document.addEventListener('keydown', e => {
      if (e.key !== 'Escape') return;
      const taskOpen = document.getElementById('task-overlay').classList.contains('open');
      const dayOpen  = document.getElementById('day-overlay').classList.contains('open');
      if (taskOpen) {
        openedFromDay ? goBackToDay() : closeTaskModal();
      } else if (dayOpen) {
        closeDayModal();
      }
    });

    // ── Init ─────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
      renderCalendar();
      document.getElementById('f-tipo').addEventListener('change', renderCalendar);
      document.getElementById('f-estado').addEventListener('change', renderCalendar);
    });
  </script>
</body>
</html>

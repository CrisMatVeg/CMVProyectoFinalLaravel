<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | Archivos — {{ $proyecto->name }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <style>
    /* ── Filtros ───────────────────────────────────── */
    .filter-bar {
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
    .filter-bar label {
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .chip-group {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }
    .chip {
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--muted);
      cursor: pointer;
      transition: all .15s;
    }
    .chip:hover   { border-color: var(--accent); color: var(--accent); }
    .chip.active  { background: var(--accent); border-color: var(--accent); color: #000; }

    .filter-select {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 5px 10px;
      font-size: 12px;
      cursor: pointer;
    }
    .filter-select:focus { outline: none; border-color: var(--accent); }

    /* ── Grid de archivos ──────────────────────────── */
    .files-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 14px;
    }

    .file-card {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 18px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      transition: border-color .2s, transform .15s;
    }
    .file-card:hover { border-color: rgba(255,255,255,.18); transform: translateY(-1px); }

    .file-card-header {
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .file-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }
    .file-icon.texto  { background: rgba(59,130,246,.15);  color: #3b82f6; }
    .file-icon.pdf    { background: rgba(239,68,68,.15);   color: #ef4444; }
    .file-icon.audio  { background: rgba(34,197,94,.15);   color: #22c55e; }
    .file-icon.imagen { background: rgba(20,184,166,.15);  color: #14b8a6; }
    .file-icon.video  { background: rgba(168,85,247,.15);  color: #a855f7; }

    .file-meta {
      flex: 1;
      min-width: 0;
    }
    .file-name {
      font-weight: 600;
      font-size: 14px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 2px;
    }
    .file-desc {
      font-size: 12px;
      color: var(--muted);
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .file-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
    }
    .file-info-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 11px;
      color: var(--muted);
      border-top: 1px solid var(--border);
      padding-top: 10px;
      margin-top: 2px;
    }
    .file-actions {
      display: flex;
      gap: 6px;
    }
    .file-btn {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      text-decoration: none;
      transition: all .15s;
    }
    .file-btn:hover         { border-color: var(--accent); color: var(--accent); }
    .file-btn.del:hover     { border-color: #ef4444; color: #ef4444; }

    /* ── Modal de subida ───────────────────────────── */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.65);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      pointer-events: none;
      transition: opacity .2s;
    }
    .modal-overlay.active {
      opacity: 1;
      pointer-events: all;
    }
    .modal-box {
      background: var(--cardgrey);
      border: 1px solid var(--border-strong);
      border-radius: var(--radius-lg);
      padding: 28px;
      width: 100%;
      max-width: 500px;
      max-height: 90vh;
      overflow-y: auto;
    }
    .modal-title {
      font-family: var(--font-title);
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 22px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 16px;
    }
    .form-group label {
      font-size: 12px;
      color: var(--muted);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .4px;
    }
    .form-group input,
    .form-group textarea {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 9px 12px;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box;
      transition: border-color .15s;
    }
    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--accent);
    }
    .form-group textarea { resize: vertical; min-height: 72px; }

    /* Drop zone */
    .drop-zone {
      border: 2px dashed var(--border);
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: border-color .2s, background .2s;
      position: relative;
    }
    .drop-zone:hover,
    .drop-zone.drag-over {
      border-color: var(--accent);
      background: rgba(20,184,166,.05);
    }
    .drop-zone input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }
    .drop-zone-icon  { font-size: 2rem; color: var(--muted); margin-bottom: 8px; }
    .drop-zone-text  { font-size: 13px; color: var(--muted); }
    .drop-zone-file  { font-size: 13px; font-weight: 600; color: var(--accent); margin-top: 6px; }

    /* Tipos checkbox grid */
    .tipos-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 7px;
    }
    .tipo-check {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 8px;
      border: 1px solid var(--border);
      cursor: pointer;
      font-size: 13px;
      transition: border-color .15s, background .15s;
    }
    .tipo-check:has(input:checked) {
      border-color: var(--accent);
      background: rgba(20,184,166,.08);
    }
    .tipo-check input { accent-color: var(--accent); }

    /* Flash messages */
    .flash {
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .flash.success { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.3); color: #4ade80; }
    .flash.error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 64px 32px;
      border: 1px dashed var(--border);
      border-radius: var(--radius-lg);
    }
    .empty-state-icon { font-size: 2.5rem; color: var(--muted); opacity: .35; margin-bottom: 16px; }
    .empty-state h3   { font-size: 15px; font-weight: 600; margin-bottom: 6px; }
    .empty-state p    { font-size: 13px; color: var(--muted); }

    /* Counter badge */
    .count-badge {
      background: rgba(255,255,255,.06);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 13px;
      color: var(--muted);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    /* No results message */
    #no-results {
      display: none;
      grid-column: 1 / -1;
      text-align: center;
      padding: 40px;
      color: var(--muted);
      font-size: 14px;
    }

    /* ── Thumbnail imagen en card ──────────────────── */
    .file-thumb {
      width: 100%;
      height: 110px;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      position: relative;
      flex-shrink: 0;
    }
    .file-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .25s;
      display: block;
    }
    .file-thumb:hover img { transform: scale(1.04); }
    .file-thumb-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,.0);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s;
    }
    .file-thumb:hover .file-thumb-overlay {
      background: rgba(0,0,0,.35);
    }
    .file-thumb-overlay i {
      font-size: 1.6rem;
      color: #fff;
      opacity: 0;
      transition: opacity .2s;
    }
    .file-thumb:hover .file-thumb-overlay i { opacity: 1; }

    /* ── Modal de previsualización ─────────────────── */
    #modal-preview .modal-box {
      max-width: 820px;
      padding: 22px 24px;
    }
    #preview-body audio {
      width: 100%;
      margin-top: 6px;
      border-radius: 8px;
      accent-color: var(--accent);
    }
    #preview-body video {
      max-width: 100%;
      max-height: 62vh;
      border-radius: 8px;
      display: block;
      margin: 0 auto;
    }
    #preview-body img {
      max-width: 100%;
      max-height: 65vh;
      object-fit: contain;
      border-radius: 8px;
      display: block;
      margin: 0 auto;
    }
    #preview-body iframe {
      width: 100%;
      height: 65vh;
      border: none;
      border-radius: 8px;
    }
    #preview-body pre {
      white-space: pre-wrap;
      word-break: break-word;
      font-size: 12.5px;
      line-height: 1.7;
      max-height: 62vh;
      overflow-y: auto;
      padding: 16px 18px;
      background: rgba(255,255,255,.03);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-family: 'Courier New', Courier, monospace;
      color: var(--white);
    }
    .preview-loading {
      text-align: center;
      padding: 48px;
      color: var(--muted);
      font-size: 14px;
    }
  </style>
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

  $catIcono = [
    'texto'  => 'fa-file-lines',
    'pdf'    => 'fa-file-pdf',
    'audio'  => 'fa-file-audio',
    'imagen' => 'fa-file-image',
    'video'  => 'fa-file-video',
  ];
@endphp

<body>
  <!-- SIDEBAR -->
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
      <a href="{{ route('proyecto.calendario', $proyecto->id) }}" class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
        <span>Calendario</span>
      </a>
      <div class="menu-item hover-lift active">
        <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
        <span>Archivos</span>
      </div>
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

  <!-- MAIN -->
  <main>
    <div class="main-content">

      <!-- Header -->
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:8px;">
        <div>
          <h1 class="title-gradient">Archivos</h1>
          <p class="subtitle">{{ $proyecto->name }}</p>
        </div>
        <button class="btn-primary hover-lift" onclick="openModal()" style="width:auto;padding:10px 20px;font-size:13px;margin-top:4px;">
          <i class="fa-solid fa-upload" style="margin-right:8px;"></i>Subir archivo
        </button>
      </div>

      <div style="margin:14px 0 24px;">
        <span class="count-badge" id="counter">
          <i class="fa-solid fa-folder"></i>
          <span id="counter-num">{{ $archivos->count() }}</span>
          {{ $archivos->count() === 1 ? 'archivo' : 'archivos' }}
        </span>
      </div>

      @if(session('success'))
        <div class="flash success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="flash error"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
      @endif

      @if($archivos->isNotEmpty())
      <!-- Filtros -->
      <div class="filter-bar">
        <div class="filter-group">
          <label>Tipo de archivo</label>
          <div class="chip-group" id="cat-chips">
            <button class="chip active" data-cat="todos">Todos</button>
            <button class="chip" data-cat="texto"><i class="fa-solid fa-file-lines" style="margin-right:4px;"></i>Texto</button>
            <button class="chip" data-cat="pdf"><i class="fa-solid fa-file-pdf" style="margin-right:4px;"></i>PDF</button>
            <button class="chip" data-cat="audio"><i class="fa-solid fa-file-audio" style="margin-right:4px;"></i>Audio</button>
            <button class="chip" data-cat="imagen"><i class="fa-solid fa-file-image" style="margin-right:4px;"></i>Imagen</button>
            <button class="chip" data-cat="video"><i class="fa-solid fa-file-video" style="margin-right:4px;"></i>Video</button>
          </div>
        </div>

        <div class="filter-group" style="margin-left:auto;">
          <label>Departamento</label>
          <select class="filter-select" id="dep-filter">
            <option value="todos">Todos los departamentos</option>
            @foreach($tipos as $tipo)
              <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="filter-group">
          <label>Buscar</label>
          <input type="text" id="search-input" placeholder="Nombre del archivo…"
                 style="background:var(--cardgrey);border:1px solid var(--border);color:var(--white);border-radius:8px;padding:5px 10px;font-size:12px;width:180px;">
        </div>
      </div>

      <!-- Grid de archivos -->
      <div class="files-grid" id="files-grid">
        @foreach($archivos as $archivo)
        @php
          $tipoIds = $archivo->tipos->pluck('id')->join(',');
          $tipoNombres = $archivo->tipos->pluck('name')->join(',');
        @endphp
        <div class="file-card"
             data-cat="{{ $archivo->categoria }}"
             data-tipos="{{ $tipoIds }}"
             data-name="{{ strtolower($archivo->name) }} {{ strtolower($archivo->description ?? '') }}">

          @if($archivo->categoria === 'imagen')
          <div class="file-thumb"
               data-preview-id="{{ $archivo->id }}"
               data-preview-cat="imagen"
               data-preview-url="{{ asset('storage/'.$archivo->path) }}"
               data-preview-name="{{ $archivo->name }}"
               onclick="openPreview(this)">
            <img src="{{ asset('storage/'.$archivo->path) }}" loading="lazy" alt="{{ $archivo->name }}">
            <div class="file-thumb-overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
          </div>
          @endif

          <div class="file-card-header">
            <div class="file-icon {{ $archivo->categoria }}">
              <i class="fa-solid {{ $catIcono[$archivo->categoria] ?? 'fa-file' }}"></i>
            </div>
            <div class="file-meta">
              <div class="file-name" title="{{ $archivo->name }}">{{ $archivo->name }}</div>
              @if($archivo->description)
                <div class="file-desc">{{ $archivo->description }}</div>
              @else
                <div class="file-desc" style="font-style:italic;opacity:.5;">Sin descripción</div>
              @endif
            </div>
          </div>

          @if($archivo->tipos->isNotEmpty())
          <div class="file-tags">
            @foreach($archivo->tipos as $tipo)
            @php $hex = $colorHex[$tipo->name] ?? '#14b8a6'; @endphp
            <span style="font-size:11px;padding:2px 10px;border-radius:20px;border:1px solid {{ $hex }};color:{{ $hex }};font-weight:600;">
              {{ $tipo->name }}
            </span>
            @endforeach
          </div>
          @endif

          <div class="file-info-row">
            <span title="{{ $archivo->original_name }}">
              <i class="fa-solid fa-paperclip" style="margin-right:4px;"></i>
              {{ $archivo->size_formateado }}
              &nbsp;·&nbsp;
              {{ $archivo->uploader ? $archivo->uploader->username : '—' }}
              &nbsp;·&nbsp;
              {{ $archivo->created_at->diffForHumans() }}
            </span>
            <div class="file-actions">
              <button class="file-btn hover-lift" title="Vista previa"
                      data-preview-id="{{ $archivo->id }}"
                      data-preview-cat="{{ $archivo->categoria }}"
                      data-preview-url="{{ asset('storage/'.$archivo->path) }}"
                      data-preview-name="{{ $archivo->name }}"
                      onclick="openPreview(this)">
                <i class="fa-solid fa-eye"></i>
              </button>
              <a href="{{ route('archivo.download', $archivo->id) }}"
                 class="file-btn hover-lift" title="Descargar">
                <i class="fa-solid fa-download"></i>
              </a>
              @if($proyecto->created_by === auth()->id() || $archivo->uploaded_by === auth()->id())
              <form action="{{ route('archivo.destroy', $archivo->id) }}" method="POST"
                    onsubmit="return confirm('¿Eliminar «{{ addslashes($archivo->name) }}»?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="file-btn del" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
              @endif
            </div>
          </div>

        </div>
        @endforeach

        <div id="no-results">
          <i class="fa-solid fa-magnifying-glass" style="margin-right:6px;"></i>
          Sin resultados para los filtros aplicados.
        </div>
      </div>

      @else
      <!-- Estado vacío -->
      <div class="empty-state">
        <div class="empty-state-icon"><i class="fa-solid fa-folder-open"></i></div>
        <h3>Todavía no hay archivos</h3>
        <p>Sube el primer archivo del proyecto con el botón de arriba.</p>
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

  <!-- MODAL SUBIDA -->
  <div class="modal-overlay" id="modal-upload">
    <div class="modal-box">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;">
        <div class="modal-title"><i class="fa-solid fa-upload" style="margin-right:10px;"></i>Subir archivo</div>
        <button onclick="closeModal()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px;">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <form action="{{ route('proyecto.archivos.store', $proyecto->id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Drop zone -->
        <div class="form-group">
          <label>Archivo <span style="color:#ef4444;">*</span></label>
          <div class="drop-zone" id="drop-zone">
            <input type="file" name="archivo" id="file-input" required
                   accept=".txt,.doc,.docx,.csv,.rtf,.odt,.pdf,.mp3,.wav,.ogg,.m4a,.flac,.aac,.jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.mp4,.mov,.avi,.webm,.mkv">
            <div class="drop-zone-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div class="drop-zone-text">Arrastra un archivo aquí o haz clic para seleccionar</div>
            <div class="drop-zone-text" style="font-size:11px;margin-top:4px;opacity:.6;">
              Texto, PDF, Audio, Imagen o Video · Máx. 50 MB
            </div>
            <div class="drop-zone-file" id="drop-zone-filename"></div>
          </div>
        </div>

        <!-- Nombre -->
        <div class="form-group">
          <label>Nombre <span style="font-weight:400;font-size:11px;">(opcional — por defecto se usa el nombre del archivo)</span></label>
          <input type="text" name="nombre" id="nombre-input" placeholder="Ej: Concept Art v2">
        </div>

        <!-- Descripción -->
        <div class="form-group">
          <label>Descripción</label>
          <textarea name="descripcion" placeholder="Describe brevemente el contenido del archivo…"></textarea>
        </div>

        <!-- Departamentos -->
        <div class="form-group">
          <label>Departamentos relacionados <span style="font-weight:400;font-size:11px;">(opcional)</span></label>
          <div class="tipos-grid">
            @foreach($tipos as $tipo)
            @php $hex = $colorHex[$tipo->name] ?? '#14b8a6'; @endphp
            <label class="tipo-check">
              <input type="checkbox" name="tipo_ids[]" value="{{ $tipo->id }}">
              <span style="width:8px;height:8px;border-radius:50%;background:{{ $hex }};flex-shrink:0;"></span>
              {{ $tipo->name }}
            </label>
            @endforeach
          </div>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
          <button type="button" onclick="closeModal()" class="btn-secondary hover-lift"
                  style="width:auto;padding:9px 18px;font-size:13px;">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift"
                  style="width:auto;padding:9px 18px;font-size:13px;">
            <i class="fa-solid fa-upload" style="margin-right:6px;"></i>Subir
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL PREVISUALIZACIÓN -->
  <div class="modal-overlay" id="modal-preview">
    <div class="modal-box">
      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
          <span id="preview-icon" style="font-size:18px;flex-shrink:0;"></span>
          <span id="preview-title" style="font-family:var(--font-title);font-size:16px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></span>
        </div>
        <div style="display:flex;gap:8px;flex-shrink:0;">
          <a id="preview-download-btn" href="#"
             class="file-btn hover-lift" title="Descargar"
             style="width:auto;padding:0 12px;font-size:12px;gap:6px;text-decoration:none;display:flex;align-items:center;">
            <i class="fa-solid fa-download"></i> Descargar
          </a>
          <button onclick="closePreview()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px;line-height:1;">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
      </div>
      <div id="preview-body"></div>
    </div>
  </div>

  <script>
    // ── Modal subida ────────────────────────────────────────
    function openModal()  { document.getElementById('modal-upload').classList.add('active'); }
    function closeModal() { document.getElementById('modal-upload').classList.remove('active'); }
    // Drop zone
    const fileInput  = document.getElementById('file-input');
    const dropZone   = document.getElementById('drop-zone');
    const fileLabel  = document.getElementById('drop-zone-filename');
    const nombreInput = document.getElementById('nombre-input');

    if (fileInput) {
      fileInput.addEventListener('change', () => updateFileName(fileInput.files[0]));
    }
    if (dropZone) {
      dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
      dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
      dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
          fileInput.files = e.dataTransfer.files;
          updateFileName(e.dataTransfer.files[0]);
        }
      });
    }

    function updateFileName(file) {
      if (!file) return;
      fileLabel.textContent = file.name;
      // Autocompletar nombre si está vacío
      if (!nombreInput.value.trim()) {
        nombreInput.placeholder = file.name.replace(/\.[^/.]+$/, '');
      }
    }

    // ── Filtros cliente ─────────────────────────────────────
    const cards      = Array.from(document.querySelectorAll('.file-card'));
    const noResults  = document.getElementById('no-results');
    const counterNum = document.getElementById('counter-num');

    let activeCat  = 'todos';
    let activeDep  = 'todos';
    let searchTerm = '';

    function applyFilters() {
      let visible = 0;
      cards.forEach(card => {
        const cat   = card.dataset.cat;
        const tipos = card.dataset.tipos ? card.dataset.tipos.split(',') : [];
        const name  = card.dataset.name || '';

        const matchCat  = activeCat === 'todos' || cat === activeCat;
        const matchDep  = activeDep === 'todos' || tipos.includes(activeDep);
        const matchSearch = !searchTerm || name.includes(searchTerm.toLowerCase());

        const show = matchCat && matchDep && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (noResults) noResults.style.display = (visible === 0 && cards.length > 0) ? 'block' : 'none';
      if (counterNum) counterNum.textContent = visible;
    }

    // Chips de categoría
    document.querySelectorAll('#cat-chips .chip').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('#cat-chips .chip').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeCat = btn.dataset.cat;
        applyFilters();
      });
    });

    // Select de departamento
    const depFilter = document.getElementById('dep-filter');
    if (depFilter) {
      depFilter.addEventListener('change', () => {
        activeDep = depFilter.value;
        applyFilters();
      });
    }

    // Búsqueda
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        searchTerm = searchInput.value.trim();
        applyFilters();
      });
    }

    // ── Previsualización ────────────────────────────────────
    const ICONS = {
      texto:  '<i class="fa-solid fa-file-lines"  style="color:#3b82f6;"></i>',
      pdf:    '<i class="fa-solid fa-file-pdf"    style="color:#ef4444;"></i>',
      audio:  '<i class="fa-solid fa-file-audio"  style="color:#22c55e;"></i>',
      imagen: '<i class="fa-solid fa-file-image"  style="color:#14b8a6;"></i>',
      video:  '<i class="fa-solid fa-file-video"  style="color:#a855f7;"></i>',
    };

    function openPreview(el) {
      const id   = el.dataset.previewId;
      const cat  = el.dataset.previewCat;
      const url  = el.dataset.previewUrl;
      const name = el.dataset.previewName;

      document.getElementById('preview-title').textContent = name;
      document.getElementById('preview-icon').innerHTML    = ICONS[cat] || '';
      document.getElementById('preview-download-btn').href =
        '{{ url("archivos") }}/' + id + '/download';

      const body = document.getElementById('preview-body');

      if (cat === 'imagen') {
        body.innerHTML = `<img src="${url}" alt="${escHtml(name)}" loading="lazy">`;

      } else if (cat === 'audio') {
        body.innerHTML = `
          <div style="padding:32px 0;text-align:center;">
            <i class="fa-solid fa-waveform-lines" style="font-size:3rem;color:#22c55e;opacity:.5;display:block;margin-bottom:20px;"></i>
            <audio controls preload="metadata">
              <source src="${url}">
              Tu navegador no soporta el reproductor de audio.
            </audio>
          </div>`;

      } else if (cat === 'video') {
        body.innerHTML = `
          <video controls preload="metadata">
            <source src="${url}">
            Tu navegador no soporta el reproductor de video.
          </video>`;

      } else if (cat === 'pdf') {
        body.innerHTML = `<iframe src="${url}" title="${escHtml(name)}"></iframe>`;

      } else if (cat === 'texto') {
        body.innerHTML = `<div class="preview-loading"><i class="fa-solid fa-spinner fa-spin"></i>&nbsp; Cargando contenido…</div>`;
        fetch('{{ url("archivos") }}/' + id + '/preview')
          .then(r => {
            if (!r.ok) throw new Error('Error al leer el archivo');
            return r.json();
          })
          .then(data => {
            let html = `<pre>${escHtml(data.content)}</pre>`;
            if (data.truncated) {
              html += `<p style="font-size:11px;color:var(--muted);text-align:center;margin-top:10px;">
                         <i class="fa-solid fa-scissors"></i>
                         Mostrando los primeros 5 000 caracteres del archivo
                       </p>`;
            }
            body.innerHTML = html;
          })
          .catch(() => {
            body.innerHTML = `<p style="color:var(--muted);text-align:center;padding:32px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                No se pudo leer el contenido del archivo.
                              </p>`;
          });
      }

      document.getElementById('modal-preview').classList.add('active');
    }

    function closePreview() {
      const body = document.getElementById('preview-body');
      // Parar audio/video si había reproducción activa
      body.querySelectorAll('audio, video').forEach(m => { m.pause(); m.src = ''; });
      body.innerHTML = '';
      document.getElementById('modal-preview').classList.remove('active');
    }

    function escHtml(str) {
      return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // Cerrar preview con Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        if (document.getElementById('modal-preview').classList.contains('active')) closePreview();
        else closeModal();
      }
    });
  </script>
</body>
</html>

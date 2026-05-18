<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | {{ $hilo->titulo }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  @livewireStyles
  @php $livewireBase = rtrim(parse_url(config('app.url'), PHP_URL_PATH) ?? '', '/'); @endphp
  <meta data-update-uri="{{ $livewireBase }}/livewire/update">
  <style>
    /* ── Cabecera del hilo ───────────────────────────── */
    .hilo-header-card {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 24px 26px;
      margin-bottom: 6px;
    }
    .hilo-header-top {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }
    .hilo-header-info { flex: 1; min-width: 0; }
    .hilo-header-title {
      font-family: var(--font-title);
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 4px;
      line-height: 1.2;
    }
    .hilo-header-meta {
      font-size: 12px;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .hilo-body-text {
      font-size: 14px;
      line-height: 1.75;
      white-space: pre-wrap;
      word-break: break-word;
      color: var(--white);
    }

    /* ── Avatar ──────────────────────────────────────── */
    .msg-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-title);
      font-size: 15px;
      font-weight: 700;
      color: #000;
      flex-shrink: 0;
    }

    /* ── Lista mensajes ──────────────────────────────── */
    .mensajes-list {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 14px;
      margin: 20px 0;
    }
    .mensaje-bubble {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      max-width: 75%;
    }
    .mensaje-content {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: 0 var(--radius-md) var(--radius-md) var(--radius-md);
      padding: 10px 13px;
      min-width: 160px;
      word-break: break-word;
    }
    /* Burbuja propia: derecha */
    .mensaje-bubble.es-mio {
      flex-direction: row-reverse;
      align-self: flex-end;
    }
    .mensaje-bubble.es-mio .mensaje-content {
      border-radius: var(--radius-md) 0 var(--radius-md) var(--radius-md);
      background: rgba(20,184,166,.1);
      border-color: rgba(20,184,166,.28);
    }
    .mensaje-author {
      display: block;
      font-weight: 700;
      font-size: 12px;
      margin-bottom: 3px;
    }
    .mensaje-body {
      display: flex;
      align-items: flex-end;
      gap: 8px;
    }
    .mensaje-meta {
      display: flex;
      align-items: center;
      gap: 4px;
      flex-shrink: 0;
      white-space: nowrap;
      margin-left: auto;
    }
    .mensaje-time {
      font-size: 11px;
      color: var(--muted);
    }
    .mensaje-texto {
      flex: 1;
      min-width: 0;
      font-size: 13.5px;
      line-height: 1.5;
      white-space: pre-wrap;
      word-break: break-word;
    }

    /* ── Adjuntos ────────────────────────────────────── */
    .msg-attachments {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-top: 12px;
    }
    .msg-adjunto {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      background: var(--bg-base, rgba(0,0,0,.2));
      border: 1px solid var(--border);
      border-radius: 8px;
    }
    .msg-adjunto-info { flex: 1; min-width: 0; }
    .msg-adjunto-name {
      font-size: 12px;
      font-weight: 600;
      display: block;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .msg-adjunto-size { font-size: 11px; color: var(--muted); }
    .msg-adjunto-actions { display: flex; gap: 6px; flex-shrink: 0; }

    .file-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      flex-shrink: 0;
    }
    .file-icon.texto  { background: rgba(59,130,246,.15);  color: #3b82f6; }
    .file-icon.pdf    { background: rgba(239,68,68,.15);   color: #ef4444; }
    .file-icon.audio  { background: rgba(34,197,94,.15);   color: #22c55e; }
    .file-icon.imagen { background: rgba(20,184,166,.15);  color: #14b8a6; }
    .file-icon.video  { background: rgba(168,85,247,.15);  color: #a855f7; }

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
    .file-btn:hover     { border-color: var(--accent); color: var(--accent); }
    .file-btn.del:hover { border-color: #ef4444; color: #ef4444; }

    /* ── Menú 3 puntos ──────────────────────────────── */
    .msg-menu { position: relative; }
    .msg-menu-btn {
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      font-size: 11px;
      padding: 2px 5px;
      border-radius: 4px;
      line-height: 1;
      transition: color .15s;
    }
    .msg-menu-btn:hover { color: var(--white); }
    .msg-menu-dropdown {
      position: absolute;
      bottom: calc(100% + 4px);
      right: 0;
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 4px;
      min-width: 120px;
      z-index: 200;
      box-shadow: 0 4px 16px rgba(0,0,0,.35);
    }
    .msg-menu-item {
      display: flex;
      align-items: center;
      gap: 8px;
      width: 100%;
      padding: 7px 10px;
      background: none;
      border: none;
      font-size: 12px;
      cursor: pointer;
      border-radius: 6px;
      text-align: left;
      transition: background .15s;
    }
    .msg-menu-item.del { color: #ef4444; }
    .msg-menu-item.del:hover { background: rgba(239,68,68,.12); }
    [x-cloak] { display: none !important; }

    /* Thumbnail imagen */
    .img-thumb-adjunto {
      max-width: 100%;
      max-height: 320px;
      object-fit: contain;
      border-radius: 8px;
      display: block;
      cursor: pointer;
      margin-top: 10px;
      border: 1px solid var(--border);
      transition: opacity .15s;
    }
    .img-thumb-adjunto:hover { opacity: .88; }

    /* ── Reply box ───────────────────────────────────── */
    .reply-box {
      position: sticky;
      bottom: 0;
      z-index: 50;
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 12px 14px;
      margin-top: 16px;
    }
    .reply-compose {
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }
    .reply-right {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .reply-right textarea {
      background: var(--bg-base, rgba(0,0,0,.2));
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 8px 10px;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box;
      resize: vertical;
      min-height: 56px;
      font-family: var(--font-main);
      transition: border-color .15s;
    }
    .reply-right textarea:focus { outline: none; border-color: var(--accent); }
    .reply-toolbar {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .attach-label {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 12px;
      color: var(--muted);
      cursor: pointer;
      transition: all .15s;
    }
    .attach-label:hover { border-color: var(--accent); color: var(--accent); }
    .reply-file-names {
      font-size: 12px;
      color: var(--accent);
      flex: 1;
    }

    /* ── Flash / separador ───────────────────────────── */
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

    .replies-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 22px 0 4px;
    }
    .replies-divider {
      flex: 1;
      height: 1px;
      background: var(--border);
    }
    .count-badge {
      background: rgba(255,255,255,.06);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 12px;
      color: var(--muted);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }

    /* ── Modal preview ───────────────────────────────── */
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
    .modal-overlay.active { opacity: 1; pointer-events: all; }
    .modal-box {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 22px 24px;
      width: 100%;
      max-width: 820px;
      max-height: 90vh;
      overflow-y: auto;
    }
    #preview-body img   { max-width:100%;max-height:65vh;object-fit:contain;border-radius:8px;display:block;margin:0 auto; }
    #preview-body video { max-width:100%;max-height:62vh;border-radius:8px;display:block;margin:0 auto; }
    #preview-body audio { width:100%;border-radius:8px;accent-color:var(--accent);margin-top:6px; }
    #preview-body iframe { width:100%;height:65vh;border:none;border-radius:8px; }
    #preview-body pre {
      white-space:pre-wrap;word-break:break-word;font-size:12.5px;line-height:1.7;
      max-height:62vh;overflow-y:auto;padding:16px 18px;
      background:rgba(255,255,255,.03);border:1px solid var(--border);
      border-radius:8px;font-family:'Courier New',monospace;color:var(--white);
    }
  </style>
</head>

@php
  $catIcono = [
    'texto'  => 'fa-file-lines',
    'pdf'    => 'fa-file-pdf',
    'audio'  => 'fa-file-audio',
    'imagen' => 'fa-file-image',
    'video'  => 'fa-file-video',
  ];
  $palette = ['#14b8a6','#ff00ff','#3b82f6','#4ade80','#a855f7','#fb923c','#f59e0b','#ec4899'];
  $autorColor = $palette[$hilo->user_id % count($palette)];
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

  <!-- MAIN -->
  <main>
    <div class="main-content">

      <!-- Breadcrumb -->
      <div style="margin-bottom:18px;">
        <a href="{{ route('proyecto.foro', $proyecto->id) }}" class="btn-ghost hover-lift"
           style="display:inline-flex;align-items:center;gap:8px;font-size:13px;padding:7px 14px;">
          <i class="fa-solid fa-arrow-left"></i> Volver al foro
        </a>
      </div>

      @if(session('success'))
        <div class="flash success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="flash error"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
      @endif

      <!-- Cabecera del hilo -->
      <div class="hilo-header-card">
        <div class="hilo-header-top">
          <div class="msg-avatar" style="background:{{ $autorColor }};">
            {{ strtoupper(substr($hilo->autor->username ?? '?', 0, 1)) }}
          </div>
          <div class="hilo-header-info">
            <h1 class="hilo-header-title title-gradient">{{ $hilo->titulo }}</h1>
            <div class="hilo-header-meta">
              <span><i class="fa-solid fa-user" style="margin-right:4px;"></i>{{ $hilo->autor->username ?? '—' }}</span>
              <span>·</span>
              <span><i class="fa-solid fa-clock" style="margin-right:4px;"></i>{{ $hilo->created_at->diffForHumans() }}</span>
            </div>
          </div>
          @if($hilo->user_id === auth()->id() || $proyecto->created_by === auth()->id())
          <form action="{{ route('proyecto.foro.destroy', [$proyecto->id, $hilo->id]) }}"
                method="POST"
                onsubmit="return confirm('¿Eliminar este hilo y todos sus mensajes?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="file-btn del" title="Eliminar hilo">
              <i class="fa-solid fa-trash"></i>
            </button>
          </form>
          @endif
        </div>

        <div class="hilo-body-text">{{ $hilo->contenido }}</div>

        @if($hiloAdjuntos->isNotEmpty())
        <div class="msg-attachments" style="margin-top:16px;">
          @foreach($hiloAdjuntos as $adj)
          @php $ico = $catIcono[$adj->categoria] ?? 'fa-file'; @endphp
          @if($adj->categoria === 'imagen')
          <img src="{{ asset('storage/'.$adj->path) }}"
               class="img-thumb-adjunto"
               alt="{{ $adj->original_name }}"
               loading="lazy"
               onclick="openPreview({ dataset: { previewId: '{{ $adj->id }}', previewCat: 'imagen', previewUrl: '{{ asset('storage/'.$adj->path) }}', previewName: '{{ addslashes($adj->original_name) }}' } })">
          @else
          <div class="msg-adjunto">
            <div class="file-icon {{ $adj->categoria }}"><i class="fa-solid {{ $ico }}"></i></div>
            <div class="msg-adjunto-info">
              <span class="msg-adjunto-name" title="{{ $adj->original_name }}">{{ $adj->original_name }}</span>
              <span class="msg-adjunto-size">{{ $adj->size_formateado }}</span>
            </div>
            <div class="msg-adjunto-actions">
              @if($adj->categoria === 'texto' || $adj->categoria === 'pdf' || $adj->categoria === 'audio' || $adj->categoria === 'video')
              <button class="file-btn" title="Vista previa"
                      onclick="openPreview(this)"
                      data-preview-id="{{ $adj->id }}"
                      data-preview-cat="{{ $adj->categoria }}"
                      data-preview-url="{{ asset('storage/'.$adj->path) }}"
                      data-preview-name="{{ addslashes($adj->original_name) }}">
                <i class="fa-solid fa-eye"></i>
              </button>
              @endif
              <a href="{{ route('foro.archivo.download', $adj->id) }}" class="file-btn" title="Descargar">
                <i class="fa-solid fa-download"></i>
              </a>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        @endif
      </div>

      {{-- Chat reactivo: Livewire + Reverb --}}
      <livewire:chat-hilo
          :proyecto-id="$proyecto->id"
          :hilo-id="$hilo->id"
          :proyecto-owner-id="$proyecto->created_by" />

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

  <!-- Modal previsualización -->
  <div class="modal-overlay" id="modal-preview">
    <div class="modal-box">
      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
          <span id="preview-icon" style="font-size:18px;flex-shrink:0;"></span>
          <span id="preview-title" style="font-family:var(--font-title);font-size:16px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></span>
        </div>
        <div style="display:flex;gap:8px;flex-shrink:0;">
          <a id="preview-download-btn" href="#"
             class="file-btn hover-lift"
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
    // ── Previsualización ───────────────────────────────
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
      document.getElementById('preview-download-btn').href = '{{ url("foro/archivos") }}/' + id + '/download';

      const body = document.getElementById('preview-body');

      if (cat === 'imagen') {
        body.innerHTML = `<img src="${url}" alt="${escHtml(name)}" loading="lazy">`;
      } else if (cat === 'audio') {
        body.innerHTML = `
          <div style="padding:32px 0;text-align:center;">
            <i class="fa-solid fa-music" style="font-size:3rem;color:#22c55e;opacity:.5;display:block;margin-bottom:20px;"></i>
            <audio controls preload="metadata"><source src="${url}">Tu navegador no soporta audio.</audio>
          </div>`;
      } else if (cat === 'video') {
        body.innerHTML = `<video controls preload="metadata"><source src="${url}">Tu navegador no soporta video.</video>`;
      } else if (cat === 'pdf') {
        body.innerHTML = `<iframe src="${url}" title="${escHtml(name)}"></iframe>`;
      } else if (cat === 'texto') {
        body.innerHTML = `<div style="text-align:center;padding:48px;color:var(--muted);"><i class="fa-solid fa-spinner fa-spin"></i> Cargando…</div>`;
        fetch('{{ url("foro/archivos") }}/' + id + '/preview')
          .then(r => { if (!r.ok) throw new Error(); return r.json(); })
          .then(data => {
            let html = `<pre>${escHtml(data.content)}</pre>`;
            if (data.truncated) html += `<p style="font-size:11px;color:var(--muted);text-align:center;margin-top:10px;"><i class="fa-solid fa-scissors"></i> Mostrando los primeros 5 000 caracteres</p>`;
            body.innerHTML = html;
          })
          .catch(() => { body.innerHTML = `<p style="color:var(--muted);text-align:center;padding:32px;"><i class="fa-solid fa-triangle-exclamation"></i> No se pudo leer el archivo.</p>`; });
      }

      document.getElementById('modal-preview').classList.add('active');
    }

    function closePreview() {
      const body = document.getElementById('preview-body');
      body.querySelectorAll('audio, video').forEach(m => { m.pause(); m.src = ''; });
      body.innerHTML = '';
      document.getElementById('modal-preview').classList.remove('active');
    }

    function escHtml(str) {
      return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreview(); });
    document.getElementById('modal-preview').addEventListener('click', function (e) {
      if (e.target === this) closePreview();
    });
  </script>
  @livewireScripts
</body>
</html>

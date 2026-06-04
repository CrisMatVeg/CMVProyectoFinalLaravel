@extends('layouts.proyecto')

@php $activo = 'foro'; @endphp

@section('titulo', 'Foro — ' . $proyecto->name)

@push('estilos')
  <style>
    /* ── Hilo cards ───────────────────────────────────── */
    .hilo-card {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 18px 20px;
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      text-decoration: none;
      color: inherit;
      margin-bottom: 10px;
      transition: border-color .2s, transform .15s, box-shadow .15s;
      position: relative;
    }
    .hilo-card:hover {
      border-color: var(--border-focus, rgba(255,255,255,.22));
      transform: translateY(-2px);
      box-shadow: 0 4px 18px rgba(0,0,0,.25);
    }

    .hilo-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-title);
      font-size: 16px;
      font-weight: 700;
      color: #000;
      flex-shrink: 0;
    }

    .hilo-body {
      flex: 1;
      min-width: 0;
    }
    .hilo-title {
      font-family: var(--font-title);
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 4px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      color: var(--white);
    }
    .hilo-snippet {
      font-size: 12.5px;
      color: var(--muted);
      margin-bottom: 6px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      line-height: 1.5;
    }
    .hilo-meta {
      display: flex;
      gap: 14px;
      font-size: 11px;
      color: var(--muted2, var(--muted));
    }
    .hilo-meta i { margin-right: 3px; }

    .hilo-stats {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 2px;
      flex-shrink: 0;
      min-width: 72px;
      text-align: center;
    }
    .hilo-stat-num {
      font-family: var(--font-title);
      font-size: 22px;
      font-weight: 700;
      color: var(--accent);
      line-height: 1;
    }
    .hilo-stat-label {
      font-size: 10px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .hilo-last-reply {
      font-size: 11px;
      color: var(--muted);
      text-align: center;
      margin-top: 4px;
    }

    /* ── Pin badge ──────────────────────────────────── */
    .pin-badge {
      position: absolute;
      top: 10px;
      right: 12px;
      font-size: 11px;
      color: var(--accent);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* ── Modal ──────────────────────────────────────── */
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
      border: 1px solid var(--border-strong, var(--border));
      border-radius: var(--radius-lg);
      padding: 28px;
      width: 100%;
      max-width: 560px;
      max-height: 92vh;
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
      background: var(--bg-base, var(--cardgrey));
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 9px 12px;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box;
      transition: border-color .15s;
      font-family: var(--font-main);
    }
    .form-group input:focus,
    .form-group textarea:focus { outline: none; border-color: var(--accent); }
    .form-group textarea { resize: vertical; min-height: 120px; }

    /* Drop zone multi-archivo */
    .drop-zone {
      border: 2px dashed var(--border);
      border-radius: 10px;
      padding: 22px;
      text-align: center;
      cursor: pointer;
      transition: border-color .2s, background .2s;
      position: relative;
    }
    .drop-zone:hover { border-color: var(--accent); background: rgba(20,184,166,.04); }
    .drop-zone input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }
    .drop-zone-icon { font-size: 1.6rem; color: var(--muted); margin-bottom: 6px; }
    .drop-zone-text { font-size: 12px; color: var(--muted); }

    .file-list-preview {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 8px;
    }
    .file-chip {
      background: rgba(20,184,166,.1);
      border: 1px solid rgba(20,184,166,.3);
      color: var(--accent);
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 200px;
    }

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

    /* Paleta de avatares de hilo (índice por user_id % 8) */
    .hilo-avatar[data-avatar-index="0"] { background: #14b8a6; }
    .hilo-avatar[data-avatar-index="1"] { background: #ff00ff; }
    .hilo-avatar[data-avatar-index="2"] { background: #3b82f6; }
    .hilo-avatar[data-avatar-index="3"] { background: #4ade80; }
    .hilo-avatar[data-avatar-index="4"] { background: #a855f7; }
    .hilo-avatar[data-avatar-index="5"] { background: #fb923c; }
    .hilo-avatar[data-avatar-index="6"] { background: #f59e0b; }
    .hilo-avatar[data-avatar-index="7"] { background: #ec4899; }
  </style>
@endpush

@section('contenido')

      <!-- Header -->
      <div class="page-header-bar">
        <div>
          <h1 class="title-gradient">Foro</h1>
          <p class="subtitle">{{ $proyecto->name }}</p>
        </div>
        <button class="btn-primary hover-lift btn-page-action" onclick="openModal()">
          <i class="fa-solid fa-plus btn-icon-mr"></i>Nuevo hilo
        </button>
      </div>

      <div class="counter-row">
        <span class="count-badge">
          <i class="fa-solid fa-comments"></i>
          {{ $hilos->count() }} {{ $hilos->count() === 1 ? 'hilo' : 'hilos' }}
        </span>
      </div>

      @if(session('success'))
        <div class="flash success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="flash error"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
      @endif

      @forelse($hilos as $hilo)
      @php $inicial = strtoupper(substr($hilo->autor->username ?? '?', 0, 1)); @endphp
      <a href="{{ route('proyecto.foro.show', [$proyecto->id, $hilo->id]) }}"
         class="hilo-card">

        <div class="hilo-avatar" data-avatar-index="{{ $hilo->user_id % 8 }}">
          {{ $inicial }}
        </div>

        <div class="hilo-body">
          <div class="hilo-title">{{ $hilo->titulo }}</div>
          <div class="hilo-snippet">{{ \Illuminate\Support\Str::limit($hilo->contenido, 130) }}</div>
          <div class="hilo-meta">
            <span><i class="fa-solid fa-user"></i>{{ $hilo->autor->username ?? '—' }}</span>
            <span><i class="fa-solid fa-clock"></i>{{ $hilo->created_at->diffForHumans() }}</span>
          </div>
        </div>

        <div class="hilo-stats">
          <div class="hilo-stat-num">{{ $hilo->mensajes_count }}</div>
          <div class="hilo-stat-label">respuestas</div>
          @if($hilo->ultimoMensaje)
          <div class="hilo-last-reply">
            Últ. {{ $hilo->ultimoMensaje->created_at->diffForHumans() }}
          </div>
          @endif
        </div>

      </a>
      @empty
      <div class="empty-state">
        <div class="empty-state-icon"><i class="fa-solid fa-comments"></i></div>
        <h3>El foro está vacío</h3>
        <p>Abre el primer hilo del proyecto con el botón de arriba.</p>
      </div>
      @endforelse

    </div>

    <x-footer />
@endsection

@push('scripts')
  <!-- MODAL: Nuevo hilo -->
  <div class="modal-overlay" id="modal-hilo">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-plus btn-icon-mr"></i>Nuevo hilo</div>
        <button onclick="closeModal()" class="modal-close-btn">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <form action="{{ route('proyecto.foro.store', $proyecto->id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label>Título <span class="required-star">*</span></label>
          <input type="text" name="titulo" required maxlength="200"
                 placeholder="Escribe el título del hilo…">
        </div>

        <div class="form-group">
          <label>Contenido <span class="required-star">*</span></label>
          <textarea name="contenido" required rows="6"
                    placeholder="Desarrolla el tema del hilo. Puedes explicar avances, escribir lore, describir una mecánica…"></textarea>
        </div>

        <div class="form-group">
          <label>Archivos adjuntos <span class="label-hint">(opcional)</span></label>
          <div class="drop-zone">
            <input type="file" name="archivos[]" id="file-input-hilo" multiple
                   accept=".txt,.doc,.docx,.csv,.rtf,.odt,.pdf,.mp3,.wav,.ogg,.m4a,.flac,.aac,.jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.mp4,.mov,.avi,.webm,.mkv">
            <div class="drop-zone-icon"><i class="fa-solid fa-paperclip"></i></div>
            <div class="drop-zone-text">Haz clic o arrastra archivos aquí · Múltiples permitidos · Máx. 50 MB c/u</div>
          </div>
          <div class="file-list-preview" id="file-list-hilo"></div>
        </div>

        <div class="modal-form-footer">
          <button type="button" onclick="closeModal()" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm">
            <i class="fa-solid fa-paper-plane btn-icon-mr-sm"></i>Publicar hilo
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal()  { document.getElementById('modal-hilo').classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeModal() { document.getElementById('modal-hilo').classList.remove('active'); document.body.style.overflow = ''; }

    document.getElementById('file-input-hilo').addEventListener('change', function () {
      const list = document.getElementById('file-list-hilo');
      list.innerHTML = '';
      Array.from(this.files).forEach(f => {
        const chip = document.createElement('span');
        chip.className = 'file-chip';
        chip.title = f.name;
        chip.textContent = f.name;
        list.appendChild(chip);
      });
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeModal();
    });

    document.getElementById('modal-hilo').addEventListener('click', function (e) {
      if (e.target === this) closeModal();
    });
  </script>
@endpush

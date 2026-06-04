@extends('layouts.proyecto')

@php $activo = 'proyecto'; @endphp

@section('titulo', $proyecto->name)

@push('estilos')
  <style>
    .proyecto-actions {
      margin-top: 10px;
      margin-bottom: 16px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .btn-sm {
      font-size: 13px;
      padding: 8px 16px;
      width: auto;
      text-decoration: none;
    }
    .alert-flash {
      margin-bottom: 20px;
      padding: 9px 14px;
      background: rgba(0,200,150,0.1);
      border: 1px solid var(--teal);
      border-radius: 6px;
      font-size: 13px;
      color: var(--teal);
    }
    .modal-backdrop {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.65);
      align-items: center;
      justify-content: center;
      z-index: 9998;
    }
    .modal-backdrop.visible { display: flex; }
    .modal-body {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 28px;
      width: 100%;
      max-width: 460px;
      position: relative;
      box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }
    .modal-close {
      position: absolute;
      top: 14px; right: 16px;
      background: none; border: none;
      color: var(--muted);
      font-size: 1.1rem;
      cursor: pointer;
      line-height: 1;
    }
    .modal-title-sm { margin-bottom: 4px; }
    .modal-sub { color: var(--muted); font-size: 13px; margin-bottom: 20px; }
    .modal-areas-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 20px;
    }
    .modal-area-label {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 12px;
      font-size: 13px;
    }
    .modal-area-label input { accent-color: var(--magenta); }
    .modal-actions-end {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    .btn-modal { font-size: 13px; }
    .invite-fields { display: flex; flex-direction: column; gap: 14px; }
    .invite-label {
      font-size: 11px;
      color: var(--muted);
      letter-spacing: .5px;
      text-transform: uppercase;
    }
    .invite-icon { color: var(--muted); font-size: 12px; flex-shrink: 0; }
    .invite-url-text {
      font-size: 12px;
      color: var(--muted);
      flex: 1;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .invite-code-text {
      font-size: 14px;
      color: var(--white);
      flex: 1;
      letter-spacing: 3px;
      font-weight: 600;
    }
    .modal-footer-end {
      margin-top: 22px;
      display: flex;
      justify-content: flex-end;
    }
  </style>
@endpush

@section('contenido')
      <!-- Título del proyecto -->
      <h1 class="title-gradient">{{ $proyecto->name }}</h1>
      <p class="subtitle">{{ $proyecto->description }}</p>

      @if($proyecto->created_by === auth()->id())
      <div class="proyecto-actions">
        <button type="button" id="open-invite-modal" class="btn-primary hover-lift btn-sm">
          <i class="fa-solid fa-link"></i> Invitar personas
        </button>
      </div>
      @endif

      @if(session('success'))
      <div class="alert-flash">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
      </div>
      @endif

      <!-- STATS GENERALES -->
      @php
        $tareasEnCurso = $proyecto->tareas->filter(fn($t) => $t->status && $t->status->name === 'En curso')->count();
      @endphp
      <section class="stats">
        <div class="card teal">
          <div class="stat-icon"><i class="fa-solid fa-people-group fa-lg"></i></div>
          <div class="stat-text">
            <div class="stat-title">Miembros</div>
            <div class="stat-value">{{ $numMiembros }}</div>
          </div>
        </div>

        <div class="card purple">
          <div class="stat-icon"><i class="fa-solid fa-folder-open fa-lg"></i></div>
          <div class="stat-text">
            <div class="stat-title">Tareas</div>
            <div class="stat-value">{{ $totalTareas }}</div>
          </div>
        </div>

        <div class="card magenta">
          <div class="stat-icon"><i class="fa-solid fa-spinner fa-lg"></i></div>
          <div class="stat-text">
            <div class="stat-title">En curso</div>
            <div class="stat-value">{{ $tareasEnCurso }}</div>
          </div>
        </div>

        <div class="card blue">
          <div class="stat-icon"><i class="fa-solid fa-bars-progress fa-lg"></i></div>
          <div class="stat-text">
            <div class="stat-title">Progreso</div>
            <div class="stat-value">{{ $progreso }}%</div>
          </div>
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
                  'Audio'      => 'green',
                  'Narrativa'  => 'purple',
                  'Marketing'  => 'orange',
                  'Arte'       => 'blue',
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
                @if($tipo->name === 'Desarrollo')
                  <div class="main-card-tag">Feature</div>
                  <div class="main-card-tag">Bug Fix</div>
                  <div class="main-card-tag">Sprint</div>
                  <div class="main-card-tag">Optimización</div>
                @elseif($tipo->name === 'Arte')
                  <div class="main-card-tag">Concept</div>
                  <div class="main-card-tag">Assets</div>
                  <div class="main-card-tag">Animación</div>
                  <div class="main-card-tag">Revisión</div>
                @elseif($tipo->name === 'Narrativa')
                  <div class="main-card-tag">Guión</div>
                  <div class="main-card-tag">Diálogos</div>
                  <div class="main-card-tag">Lore</div>
                  <div class="main-card-tag">Traducción</div>
                @elseif($tipo->name === 'Diseño')
                  <div class="main-card-tag">UI/UX</div>
                  <div class="main-card-tag">Mecánicas</div>
                  <div class="main-card-tag">Level Design</div>
                  <div class="main-card-tag">Balanceo</div>
                @elseif($tipo->name === 'Audio')
                  <div class="main-card-tag">SFX</div>
                  <div class="main-card-tag">Música</div>
                  <div class="main-card-tag">Mezcla</div>
                  <div class="main-card-tag">Implementación</div>
                @elseif($tipo->name === 'Marketing')
                  <div class="main-card-tag">Redes</div>
                  <div class="main-card-tag">Trailer</div>
                  <div class="main-card-tag">Comunidad</div>
                  <div class="main-card-tag">Analytics</div>
                @else
                  <div class="main-card-tag">Tareas</div>
                  <div class="main-card-tag">Sprint</div>
                  <div class="main-card-tag">Revisión</div>
                  <div class="main-card-tag">Entrega</div>
                @endif
              </div>
            </a>
          @endforeach
        </div>
      </section>

    </div>

    <x-footer />
@endsection

@push('scripts')
  @if($proyecto->created_by === auth()->id())
  <!-- MODAL SELECCIÓN DE ÁREAS -->
  <div id="areas-invite-modal" class="modal-backdrop">
    <div class="modal-body">
      <button id="close-areas-modal" class="modal-close">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <h2 class="title-gradient modal-title-sm">Invitar al proyecto</h2>
      <p class="modal-sub">Selecciona las áreas a las que tendrá acceso el invitado.</p>
      <form method="POST" action="{{ route('invitacion.generar', $proyecto->id) }}">
        @csrf
        <div class="modal-areas-grid">
          @foreach($tipos as $tipo)
          <label class="modal-area-label">
            <input type="checkbox" name="areas[]" value="{{ $tipo->id }}">
            {{ $tipo->name }}
          </label>
          @endforeach
        </div>
        <div class="modal-actions-end">
          <button type="submit" class="btn-primary hover-lift btn-modal">Generar enlace</button>
          <button type="button" id="cancel-areas-modal" class="btn-secondary hover-lift btn-modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const areasModal = document.getElementById('areas-invite-modal');
    const openBtn = document.getElementById('open-invite-modal');
    const closeAreasModal = () => areasModal.classList.remove('visible');

    if (openBtn) {
      openBtn.addEventListener('click', () => areasModal.classList.add('visible'));
    }
    document.getElementById('close-areas-modal').addEventListener('click', closeAreasModal);
    document.getElementById('cancel-areas-modal').addEventListener('click', closeAreasModal);
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

      <button id="close-invite-modal" class="modal-close">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <h2 class="title-gradient modal-title-sm">Enlace de invitación</h2>
      <p class="modal-sub">Válido 7 días · Comparte el enlace o el código</p>

      <div class="invite-fields">
        <div>
          <label class="invite-label">Enlace</label>
          <div class="invite-row">
            <i class="fa-solid fa-link invite-icon"></i>
            <span class="invite-url-text">{{ session('invite_url') }}</span>
            <button type="button" class="invite-copy-btn" data-value="{{ session('invite_url') }}" data-label="Copiar enlace">Copiar enlace</button>
          </div>
        </div>
        <div>
          <label class="invite-label">Código</label>
          <div class="invite-row">
            <i class="fa-solid fa-key invite-icon"></i>
            <span class="invite-code-text">{{ session('invite_code') }}</span>
            <button type="button" class="invite-copy-btn" data-value="{{ session('invite_code') }}" data-label="Copiar código">Copiar código</button>
          </div>
        </div>
      </div>

      <div class="modal-footer-end">
        <button id="close-invite-modal-btn" class="btn-secondary hover-lift btn-modal">Cerrar</button>
      </div>

    </div>
  </div>

  <script>
    const inviteModal = document.getElementById('invite-modal');
    const closeInviteModal = () => inviteModal.style.display = 'none';

    document.getElementById('close-invite-modal').addEventListener('click', closeInviteModal);
    document.getElementById('close-invite-modal-btn').addEventListener('click', closeInviteModal);

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
@endpush
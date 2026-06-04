@extends('layouts.proyecto')

@php $activo = 'predefinicion'; @endphp

@section('titulo', 'Predefinición — ' . $proyecto->name)

@push('estilos')
  <style>
    /* ── Tabs ──────────────────────────────────────────────── */
    .tabs-bar {
      display: flex;
      gap: 4px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 28px;
    }
    .tab-btn {
      padding: 10px 22px;
      border: none;
      background: transparent;
      color: var(--muted);
      font-family: var(--font-main);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      margin-bottom: -1px;
      transition: color .15s, border-color .15s;
    }
    .tab-btn:hover  { color: var(--white); }
    .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* ── Tabla ─────────────────────────────────────────────── */
    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .data-table th {
      text-align: left;
      padding: 10px 14px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: var(--muted);
      border-bottom: 1px solid var(--border);
    }
    .data-table td {
      padding: 11px 14px;
      border-bottom: 1px solid rgba(255,255,255,.04);
      vertical-align: middle;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: rgba(255,255,255,.02); }

    .game-id-badge {
      font-family: 'Courier New', monospace;
      font-size: 12px;
      background: rgba(20,184,166,.12);
      border: 1px solid rgba(20,184,166,.3);
      color: var(--accent);
      padding: 3px 10px;
      border-radius: 6px;
      display: inline-block;
    }
    .tipo-badge {
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
    }
    .tipo-arma       { background: rgba(239,68,68,.15);  border: 1px solid #ef4444; color: #ef4444; }
    .tipo-consumible { background: rgba(34,197,94,.15);  border: 1px solid #22c55e; color: #22c55e; }
    .tipo-mision     { background: rgba(168,85,247,.15); border: 1px solid #a855f7; color: #a855f7; }

    /* ── Row actions ────────────────────────────────────────── */
    .row-actions { display: flex; gap: 6px; }
    .row-btn {
      width: 28px; height: 28px;
      border-radius: 7px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--muted);
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px;
      transition: all .15s;
    }
    .row-btn:hover     { border-color: var(--accent); color: var(--accent); }
    .row-btn.del:hover { border-color: #ef4444; color: #ef4444; }

    /* ── Sección header ─────────────────────────────────────── */
    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }
    .section-title {
      font-family: var(--font-title);
      font-size: 16px;
      font-weight: 700;
    }

    /* ── Export panel ───────────────────────────────────────── */
    .export-panel {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 22px 24px;
      margin-bottom: 28px;
    }
    .export-title {
      font-family: var(--font-title);
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .motor-grid {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .motor-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 18px;
      border-radius: 10px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--white);
      font-family: var(--font-main);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all .2s;
    }
    .motor-btn:hover { transform: translateY(-2px); }
    .motor-btn.unity:hover  { background: rgba(59,130,246,.12);  border-color: #3b82f6; color: #3b82f6; }
    .motor-btn.unreal:hover { background: rgba(239,68,68,.12);   border-color: #ef4444; color: #ef4444; }
    .motor-btn.godot:hover  { background: rgba(74,222,128,.12);  border-color: #4ade80; color: #4ade80; }

    .motor-icon {
      width: 28px; height: 28px;
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }
    .motor-icon.unity  { background: rgba(59,130,246,.2);  color: #3b82f6; }
    .motor-icon.unreal { background: rgba(239,68,68,.2);   color: #ef4444; }
    .motor-icon.godot  { background: rgba(74,222,128,.2);  color: #4ade80; }

    /* ── Modales ────────────────────────────────────────────── */
    .modal-overlay {
      position: fixed; inset: 0;
      background: rgba(0,0,0,.65);
      display: flex; align-items: center; justify-content: center;
      z-index: 1000;
      opacity: 0; pointer-events: none;
      transition: opacity .2s;
    }
    .modal-overlay.active { opacity: 1; pointer-events: all; }
    .modal-box {
      background: var(--cardgrey);
      border: 1px solid var(--border-strong);
      border-radius: var(--radius-lg);
      padding: 28px;
      width: 100%; max-width: 480px;
      max-height: 90vh; overflow-y: auto;
    }
    .modal-title {
      font-family: var(--font-title);
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .form-group {
      display: flex; flex-direction: column; gap: 6px;
      margin-bottom: 14px;
    }
    .form-group label {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .4px;
      color: var(--muted);
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
      background: var(--cardgrey);
      border: 1px solid var(--border);
      color: var(--white);
      border-radius: 8px;
      padding: 9px 12px;
      font-size: 13px;
      font-family: var(--font-main);
      width: 100%; box-sizing: border-box;
      transition: border-color .15s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: var(--accent); }
    .form-group select option { background: var(--cardgrey); }
    .form-group textarea { resize: vertical; min-height: 72px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; }

    /* ── Flash ──────────────────────────────────────────────── */
    .flash {
      padding: 12px 16px; border-radius: 8px; font-size: 13px;
      margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
    }
    .flash.success { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.3); color: #4ade80; }
    .flash.error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }

    /* ── Empty state ────────────────────────────────────────── */
    .empty-row td {
      text-align: center; padding: 40px; color: var(--muted);
      font-size: 13px; font-style: italic;
    }

    /* ── Stat chip ──────────────────────────────────────────── */
    .stat-chip {
      background: rgba(255,255,255,.05);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 11px; color: var(--muted);
      margin-left: 8px;
    }

    .header-mb-24        { margin-bottom: 24px; }
    .export-desc         { font-size: 13px; color: var(--muted); margin-bottom: 16px; }
    .export-warn         { font-size: 13px; color: #f87171; margin-bottom: 12px; }
    .motor-disabled      { opacity: .4; cursor: not-allowed; }
    .motor-subtitle      { font-size: 11px; font-weight: 400; opacity: .7; }
    .motor-download-icon { margin-left: auto; font-size: 12px; opacity: .6; }
    .section-icon-accent { color: var(--accent); margin-right: 8px; }
    .section-icon-purple { color: #a855f7; margin-right: 8px; }
    .section-icon-orange { color: #fb923c; margin-right: 8px; }
    .icon-accent         { color: var(--accent); }
    .table-wrapper       { background: var(--cardgrey); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
    .th-center           { text-align: center; }
    .td-bold             { font-weight: 600; }
    .td-center-green     { text-align: center; color: #4ade80; }
    .td-center-red       { text-align: center; color: #ef4444; }
    .td-center-blue      { text-align: center; color: #3b82f6; }
    .td-center-orange    { text-align: center; color: #fb923c; }
    .td-center-bold      { text-align: center; font-weight: 600; }
    .td-right            { text-align: right; }
    .row-actions-end     { justify-content: flex-end; }
    .empty-icon          { opacity: .25; font-size: 1.5rem; display: block; margin-bottom: 10px; }
    .td-muted-200        { color: var(--muted); max-width: 200px; }
    .text-clamp-2        { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .td-order            { text-align: center; font-family: 'Courier New', monospace; font-weight: 700; color: var(--accent); }
    .td-mono-muted       { font-family: 'Courier New', monospace; font-size: 12px; color: var(--muted); }
    .td-max-260          { max-width: 260px; }
    .textarea-tall       { min-height: 90px; }
  </style>
@endpush

@section('contenido')

      <!-- Header -->
      <div class="page-header-bar header-mb-24">
        <div>
          <h1 class="title-gradient">Predefinición de Datos</h1>
          <p class="subtitle">{{ $proyecto->name }}</p>
        </div>
      </div>

      @if(session('success'))
        <div class="flash success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="flash error"><i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}</div>
      @endif

      <!-- Panel de exportación -->
      <div class="export-panel">
        <div class="export-title">
          <i class="fa-solid fa-file-export icon-accent"></i>
          Exportar datos del juego
        </div>
        <p class="export-desc">
          Selecciona el motor destino. El JSON se adapta automáticamente a su formato.
        </p>
        @php $hayDatos = $personajes->count() + $items->count() + $dialogos->count() > 0; @endphp
        @if(!$hayDatos)
          <p class="export-warn">
            <i class="fa-solid fa-circle-exclamation btn-icon-mr-sm"></i>
            No hay datos para exportar. Crea al menos un personaje, ítem o diálogo.
          </p>
        @endif
        <div class="motor-grid">
          <button class="motor-btn unity hover-lift {{ !$hayDatos ? 'motor-disabled' : '' }}" onclick="exportarDatos('unity')" {{ !$hayDatos ? 'disabled' : '' }}>
            <div class="motor-icon unity"><i class="fa-solid fa-cube"></i></div>
            <div>
              <div>Unity</div>
              <div class="motor-subtitle">Nomenclatura C# (m_Field)</div>
            </div>
            <i class="fa-solid fa-download motor-download-icon"></i>
          </button>
          <button class="motor-btn unreal hover-lift {{ !$hayDatos ? 'motor-disabled' : '' }}" onclick="exportarDatos('unreal')" {{ !$hayDatos ? 'disabled' : '' }}>
            <div class="motor-icon unreal"><i class="fa-solid fa-dragon"></i></div>
            <div>
              <div>Unreal Engine</div>
              <div class="motor-subtitle">Array con clave "Name" (DataTable)</div>
            </div>
            <i class="fa-solid fa-download motor-download-icon"></i>
          </button>
          <button class="motor-btn godot hover-lift {{ !$hayDatos ? 'motor-disabled' : '' }}" onclick="exportarDatos('godot')" {{ !$hayDatos ? 'disabled' : '' }}>
            <div class="motor-icon godot"><i class="fa-solid fa-robot"></i></div>
            <div>
              <div>Godot</div>
              <div class="motor-subtitle">Diccionario anidado por ID</div>
            </div>
            <i class="fa-solid fa-download motor-download-icon"></i>
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="tabs-bar">
        <button class="tab-btn active" onclick="switchTab(this, 'personajes')">
          <i class="fa-solid fa-person-running btn-icon-mr-sm"></i>
          Personajes
          <span class="stat-chip">{{ $personajes->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab(this, 'items')">
          <i class="fa-solid fa-swords btn-icon-mr-sm"></i>
          Ítems
          <span class="stat-chip">{{ $items->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab(this, 'dialogos')">
          <i class="fa-solid fa-comments btn-icon-mr-sm"></i>
          Diálogos
          <span class="stat-chip">{{ $dialogos->count() }}</span>
        </button>
      </div>

      <!-- ═══════════ PANEL PERSONAJES ═══════════ -->
      <div class="tab-panel active" id="panel-personajes">
        <div class="section-header">
          <div class="section-title">
            <i class="fa-solid fa-person-running section-icon-accent"></i>Personajes
          </div>
          <button class="btn-primary hover-lift btn-modal-sm" onclick="openModal('modal-add-personaje')">
            <i class="fa-solid fa-plus btn-icon-mr-sm"></i>Nuevo Personaje
          </button>
        </div>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th class="th-center">Vida</th>
                <th class="th-center">Ataque</th>
                <th class="th-center">Defensa</th>
                <th class="th-center">Velocidad</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($personajes as $p)
              <tr>
                <td><span class="game-id-badge">{{ $p->game_id }}</span></td>
                <td class="td-bold">{{ $p->nombre }}</td>
                <td class="td-center-green">{{ $p->vida }}</td>
                <td class="td-center-red">{{ $p->ataque }}</td>
                <td class="td-center-blue">{{ $p->defensa }}</td>
                <td class="td-center-orange">{{ $p->velocidad }}</td>
                <td class="td-right">
                  <div class="row-actions row-actions-end">
                    <button class="row-btn" title="Editar"
                            onclick="openEditPersonaje({{ $p->id }}, '{{ addslashes($p->game_id) }}', '{{ addslashes($p->nombre) }}', {{ $p->vida }}, {{ $p->ataque }}, {{ $p->defensa }}, {{ $p->velocidad }})">
                      <i class="fa-solid fa-pen"></i>
                    </button>
                    <form action="{{ route('proyecto.predefinicion.personajes.destroy', [$proyecto->id, $p->id]) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar personaje «{{ addslashes($p->nombre) }}»?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="row-btn del"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr class="empty-row">
                <td colspan="7">
                  <i class="fa-solid fa-person-running empty-icon"></i>
                  Sin personajes — crea el primero con el botón de arriba.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- ═══════════ PANEL ÍTEMS ═══════════ -->
      <div class="tab-panel" id="panel-items">
        <div class="section-header">
          <div class="section-title">
            <i class="fa-solid fa-swords section-icon-purple"></i>Ítems
          </div>
          <button class="btn-primary hover-lift btn-modal-sm" onclick="openModal('modal-add-item')">
            <i class="fa-solid fa-plus btn-icon-mr-sm"></i>Nuevo Ítem
          </button>
        </div>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="th-center">Precio</th>
                <th class="th-center">Tipo</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $item)
              <tr>
                <td><span class="game-id-badge">{{ $item->game_id }}</span></td>
                <td class="td-bold">{{ $item->nombre }}</td>
                <td class="td-muted-200">
                  <span class="text-clamp-2">
                    {{ $item->descripcion ?: '—' }}
                  </span>
                </td>
                <td class="td-center-bold">{{ number_format($item->precio) }}</td>
                <td class="th-center">
                  @php $tc = match($item->tipo) { 'Arma' => 'tipo-arma', 'Consumible' => 'tipo-consumible', 'Misión' => 'tipo-mision', default => '' }; @endphp
                  <span class="tipo-badge {{ $tc }}">{{ $item->tipo }}</span>
                </td>
                <td class="td-right">
                  <div class="row-actions row-actions-end">
                    <button class="row-btn" title="Editar"
                            onclick="openEditItem({{ $item->id }}, '{{ addslashes($item->game_id) }}', '{{ addslashes($item->nombre) }}', '{{ addslashes($item->descripcion ?? '') }}', {{ $item->precio }}, '{{ $item->tipo }}')">
                      <i class="fa-solid fa-pen"></i>
                    </button>
                    <form action="{{ route('proyecto.predefinicion.items.destroy', [$proyecto->id, $item->id]) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar ítem «{{ addslashes($item->nombre) }}»?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="row-btn del"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr class="empty-row">
                <td colspan="6">
                  <i class="fa-solid fa-swords empty-icon"></i>
                  Sin ítems — crea el primero con el botón de arriba.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- ═══════════ PANEL DIÁLOGOS ═══════════ -->
      <div class="tab-panel" id="panel-dialogos">
        <div class="section-header">
          <div class="section-title">
            <i class="fa-solid fa-comments section-icon-orange"></i>Diálogos
          </div>
          <button class="btn-primary hover-lift btn-modal-sm" onclick="openModal('modal-add-dialogo')">
            <i class="fa-solid fa-plus btn-icon-mr-sm"></i>Nueva Línea
          </button>
        </div>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID Conversación</th>
                <th class="th-center">Orden</th>
                <th>Personaje ID</th>
                <th>Texto</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($dialogos as $d)
              <tr>
                <td><span class="game-id-badge">{{ $d->id_conversacion }}</span></td>
                <td class="td-order">{{ $d->orden }}</td>
                <td class="td-mono-muted">{{ $d->personaje_id }}</td>
                <td class="td-max-260">
                  <span class="text-clamp-2">{{ $d->texto }}</span>
                </td>
                <td class="td-right">
                  <div class="row-actions row-actions-end">
                    <button class="row-btn" title="Editar"
                            onclick="openEditDialogo({{ $d->id }}, '{{ addslashes($d->id_conversacion) }}', {{ $d->orden }}, '{{ addslashes($d->personaje_id) }}', '{{ addslashes($d->texto) }}')">
                      <i class="fa-solid fa-pen"></i>
                    </button>
                    <form action="{{ route('proyecto.predefinicion.dialogos.destroy', [$proyecto->id, $d->id]) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar esta línea de diálogo?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="row-btn del"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr class="empty-row">
                <td colspan="5">
                  <i class="fa-solid fa-comments empty-icon"></i>
                  Sin diálogos — añade la primera línea con el botón de arriba.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div><!-- /main-content -->

    <x-footer />
@endsection

@push('scripts')
  <!-- ══════════ MODAL CREAR PERSONAJE ══════════ -->
  <div class="modal-overlay" id="modal-add-personaje">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-person-running section-icon-accent"></i>Nuevo Personaje</div>
        <button onclick="closeModal('modal-add-personaje')" class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form action="{{ route('proyecto.predefinicion.personajes.store', $proyecto->id) }}" method="POST">
        @csrf
        <div class="form-row">
          <div class="form-group"><label>ID *</label><input type="text" name="game_id" placeholder="ej: CHAR_001" required></div>
          <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" placeholder="ej: Guerrero" required></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Vida (Int) *</label><input type="number" name="vida" min="0" placeholder="100" required></div>
          <div class="form-group"><label>Ataque (Int) *</label><input type="number" name="ataque" min="0" placeholder="25" required></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Defensa (Int) *</label><input type="number" name="defensa" min="0" placeholder="15" required></div>
          <div class="form-group"><label>Velocidad (Float) *</label><input type="number" name="velocidad" min="0" step="0.01" placeholder="3.5" required></div>
        </div>
        <div class="modal-actions">
          <button type="button" onclick="closeModal('modal-add-personaje')" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm"><i class="fa-solid fa-plus btn-icon-mr-sm"></i>Crear</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDITAR PERSONAJE -->
  <div class="modal-overlay" id="modal-edit-personaje">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-pen section-icon-accent"></i>Editar Personaje</div>
        <button onclick="closeModal('modal-edit-personaje')" class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form id="form-edit-personaje" method="POST">
        @csrf @method('PUT')
        <div class="form-row">
          <div class="form-group"><label>ID *</label><input type="text" name="game_id" id="edit-p-game_id" required></div>
          <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" id="edit-p-nombre" required></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Vida (Int) *</label><input type="number" name="vida" id="edit-p-vida" min="0" required></div>
          <div class="form-group"><label>Ataque (Int) *</label><input type="number" name="ataque" id="edit-p-ataque" min="0" required></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Defensa (Int) *</label><input type="number" name="defensa" id="edit-p-defensa" min="0" required></div>
          <div class="form-group"><label>Velocidad (Float) *</label><input type="number" name="velocidad" id="edit-p-velocidad" min="0" step="0.01" required></div>
        </div>
        <div class="modal-actions">
          <button type="button" onclick="closeModal('modal-edit-personaje')" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm"><i class="fa-solid fa-check btn-icon-mr-sm"></i>Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ══════════ MODAL CREAR ÍTEM ══════════ -->
  <div class="modal-overlay" id="modal-add-item">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-swords section-icon-purple"></i>Nuevo Ítem</div>
        <button onclick="closeModal('modal-add-item')" class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form action="{{ route('proyecto.predefinicion.items.store', $proyecto->id) }}" method="POST">
        @csrf
        <div class="form-row">
          <div class="form-group"><label>ID *</label><input type="text" name="game_id" placeholder="ej: ITEM_001" required></div>
          <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" placeholder="ej: Espada" required></div>
        </div>
        <div class="form-group"><label>Descripción</label><textarea name="descripcion" placeholder="Describe el ítem…"></textarea></div>
        <div class="form-row">
          <div class="form-group"><label>Precio (Int) *</label><input type="number" name="precio" min="0" placeholder="500" required></div>
          <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" required>
              <option value="" disabled selected>Seleccionar…</option>
              <option value="Arma">Arma</option>
              <option value="Consumible">Consumible</option>
              <option value="Misión">Misión</option>
            </select>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" onclick="closeModal('modal-add-item')" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm"><i class="fa-solid fa-plus btn-icon-mr-sm"></i>Crear</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDITAR ÍTEM -->
  <div class="modal-overlay" id="modal-edit-item">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-pen section-icon-purple"></i>Editar Ítem</div>
        <button onclick="closeModal('modal-edit-item')" class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form id="form-edit-item" method="POST">
        @csrf @method('PUT')
        <div class="form-row">
          <div class="form-group"><label>ID *</label><input type="text" name="game_id" id="edit-i-game_id" required></div>
          <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" id="edit-i-nombre" required></div>
        </div>
        <div class="form-group"><label>Descripción</label><textarea name="descripcion" id="edit-i-descripcion"></textarea></div>
        <div class="form-row">
          <div class="form-group"><label>Precio (Int) *</label><input type="number" name="precio" id="edit-i-precio" min="0" required></div>
          <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" id="edit-i-tipo" required>
              <option value="Arma">Arma</option>
              <option value="Consumible">Consumible</option>
              <option value="Misión">Misión</option>
            </select>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" onclick="closeModal('modal-edit-item')" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm"><i class="fa-solid fa-check btn-icon-mr-sm"></i>Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ══════════ MODAL CREAR DIÁLOGO ══════════ -->
  <div class="modal-overlay" id="modal-add-dialogo">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-comments section-icon-orange"></i>Nueva Línea de Diálogo</div>
        <button onclick="closeModal('modal-add-dialogo')" class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form action="{{ route('proyecto.predefinicion.dialogos.store', $proyecto->id) }}" method="POST">
        @csrf
        <div class="form-row">
          <div class="form-group"><label>ID Conversación *</label><input type="text" name="id_conversacion" placeholder="ej: CONV_001" required></div>
          <div class="form-group"><label>Orden (Int) *</label><input type="number" name="orden" min="0" placeholder="1" required></div>
        </div>
        <div class="form-group"><label>Personaje ID *</label><input type="text" name="personaje_id" placeholder="ej: CHAR_001" required></div>
        <div class="form-group"><label>Texto *</label><textarea name="texto" placeholder="Escribe el diálogo…" required class="textarea-tall"></textarea></div>
        <div class="modal-actions">
          <button type="button" onclick="closeModal('modal-add-dialogo')" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm"><i class="fa-solid fa-plus btn-icon-mr-sm"></i>Crear</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDITAR DIÁLOGO -->
  <div class="modal-overlay" id="modal-edit-dialogo">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-pen section-icon-orange"></i>Editar Diálogo</div>
        <button onclick="closeModal('modal-edit-dialogo')" class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form id="form-edit-dialogo" method="POST">
        @csrf @method('PUT')
        <div class="form-row">
          <div class="form-group"><label>ID Conversación *</label><input type="text" name="id_conversacion" id="edit-d-id_conv" required></div>
          <div class="form-group"><label>Orden (Int) *</label><input type="number" name="orden" id="edit-d-orden" min="0" required></div>
        </div>
        <div class="form-group"><label>Personaje ID *</label><input type="text" name="personaje_id" id="edit-d-personaje_id" required></div>
        <div class="form-group"><label>Texto *</label><textarea name="texto" id="edit-d-texto" required class="textarea-tall"></textarea></div>
        <div class="modal-actions">
          <button type="button" onclick="closeModal('modal-edit-dialogo')" class="btn-secondary hover-lift btn-modal-sm">Cancelar</button>
          <button type="submit" class="btn-primary hover-lift btn-modal-sm"><i class="fa-solid fa-check btn-icon-mr-sm"></i>Guardar</button>
        </div>
      </form>
    </div>
  </div>

@php
  $personajesJs = $personajes->map(function($p) {
    return ['game_id' => $p->game_id, 'nombre' => $p->nombre, 'vida' => $p->vida, 'ataque' => $p->ataque, 'defensa' => $p->defensa, 'velocidad' => (float)$p->velocidad];
  });
  $itemsJs = $items->map(function($i) {
    return ['game_id' => $i->game_id, 'nombre' => $i->nombre, 'descripcion' => $i->descripcion ?? '', 'precio' => $i->precio, 'tipo' => $i->tipo];
  });
  $dialogosJs = $dialogos->map(function($d) {
    return ['id_conversacion' => $d->id_conversacion, 'orden' => $d->orden, 'personaje_id' => $d->personaje_id, 'texto' => $d->texto];
  });
@endphp
  <!-- Datos PHP → JS para el exportador -->
  <script>
    const GAME_DATA = {
      personajes: @json($personajesJs),
      items:      @json($itemsJs),
      dialogos:   @json($dialogosJs),
    };
  </script>

  <script>
    // ── Tabs ────────────────────────────────────────────────────
    function switchTab(btn, tab) {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('panel-' + tab).classList.add('active');
    }

    // ── Modales ──────────────────────────────────────────────────
    function openModal(id)  { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    // ── Editar Personaje ─────────────────────────────────────────
    function openEditPersonaje(id, game_id, nombre, vida, ataque, defensa, velocidad) {
      document.getElementById('form-edit-personaje').action = '{{ url("proyecto/'.$proyecto->id.'/predefinicion/personajes") }}/' + id;
      document.getElementById('edit-p-game_id').value   = game_id;
      document.getElementById('edit-p-nombre').value    = nombre;
      document.getElementById('edit-p-vida').value      = vida;
      document.getElementById('edit-p-ataque').value    = ataque;
      document.getElementById('edit-p-defensa').value   = defensa;
      document.getElementById('edit-p-velocidad').value = velocidad;
      openModal('modal-edit-personaje');
    }

    // ── Editar Ítem ──────────────────────────────────────────────
    function openEditItem(id, game_id, nombre, descripcion, precio, tipo) {
      document.getElementById('form-edit-item').action = '{{ url("proyecto/'.$proyecto->id.'/predefinicion/items") }}/' + id;
      document.getElementById('edit-i-game_id').value     = game_id;
      document.getElementById('edit-i-nombre').value      = nombre;
      document.getElementById('edit-i-descripcion').value = descripcion;
      document.getElementById('edit-i-precio').value      = precio;
      document.getElementById('edit-i-tipo').value        = tipo;
      openModal('modal-edit-item');
    }

    // ── Editar Diálogo ───────────────────────────────────────────
    function openEditDialogo(id, id_conv, orden, personaje_id, texto) {
      document.getElementById('form-edit-dialogo').action = '{{ url("proyecto/'.$proyecto->id.'/predefinicion/dialogos") }}/' + id;
      document.getElementById('edit-d-id_conv').value      = id_conv;
      document.getElementById('edit-d-orden').value        = orden;
      document.getElementById('edit-d-personaje_id').value = personaje_id;
      document.getElementById('edit-d-texto').value        = texto;
      openModal('modal-edit-dialogo');
    }

    // ══════════════════════════════════════════════════════════════
    //  EXPORTADOR — convierte los datos al formato de cada motor
    // ══════════════════════════════════════════════════════════════
    function exportarDatos(motor) {
      const { personajes, items, dialogos } = GAME_DATA;
      let resultado;

      if (motor === 'unity') {
        resultado = {
          PersonajeList: {
            Items: personajes.map(p => ({ m_ID: p.game_id, m_Nombre: p.nombre, m_Health: p.vida, m_Attack: p.ataque, m_Defense: p.defensa, m_Speed: p.velocidad })),
          },
          ItemList: {
            Items: items.map(i => ({ m_ID: i.game_id, m_Nombre: i.nombre, m_Description: i.descripcion, m_Price: i.precio, m_Type: i.tipo })),
          },
          DialogoList: {
            Items: dialogos.map(d => ({ m_ConversationID: d.id_conversacion, m_Order: d.orden, m_CharacterID: d.personaje_id, m_Text: d.texto })),
          },
        };

      } else if (motor === 'unreal') {
        resultado = {
          Personajes: personajes.map(p => ({ Name: p.game_id, Nombre: p.nombre, Vida: p.vida, Ataque: p.ataque, Defensa: p.defensa, Velocidad: p.velocidad })),
          Items:      items.map(i => ({ Name: i.game_id, Nombre: i.nombre, Descripcion: i.descripcion, Precio: i.precio, Tipo: i.tipo })),
          Dialogos:   dialogos.map(d => ({ Name: d.id_conversacion + '_' + String(d.orden).padStart(3, '0'), ID_Conversacion: d.id_conversacion, Orden: d.orden, Personaje_ID: d.personaje_id, Texto: d.texto })),
        };

      } else if (motor === 'godot') {
        const personajesDict = {};
        personajes.forEach(p => { personajesDict[p.game_id] = { nombre: p.nombre, vida: p.vida, ataque: p.ataque, defensa: p.defensa, velocidad: p.velocidad }; });

        const itemsDict = {};
        items.forEach(i => { itemsDict[i.game_id] = { nombre: i.nombre, descripcion: i.descripcion, precio: i.precio, tipo: i.tipo }; });

        const dialogosDict = {};
        dialogos.forEach(d => {
          if (!dialogosDict[d.id_conversacion]) dialogosDict[d.id_conversacion] = {};
          dialogosDict[d.id_conversacion][d.orden] = { personaje_id: d.personaje_id, texto: d.texto };
        });

        resultado = { personajes: personajesDict, items: itemsDict, dialogos: dialogosDict };
      }

      const blob = new Blob([JSON.stringify(resultado, null, 2)], { type: 'application/json' });
      const url  = URL.createObjectURL(blob);
      const a    = document.createElement('a');
      a.href = url;
      a.download = `gamedata_${motor}_{{ $proyecto->id }}.json`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }
  </script>
@endpush

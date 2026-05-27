<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
  <title>PIXEL | Proyectos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')

  <style>
    /* ======================================================
   PAGE LAYOUT (VERTICAL)
====================================================== */
    body {
      flex-direction: column;
    }

    main {
      display: block;
      min-height: 0vh;
    }

    .logo {
      margin: 0;
    }

    header>div:last-child {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .header-perfil-link {
      text-decoration: none;
    }

    /* ======================================================
       CONTENT
    ====================================================== */
    .projects-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 28px;
      display: flex;
      flex-direction: column;
      gap: 40px;
    }


    .projects-section h2 {
      font-size: var(--title-size-md);
    }

    .projects-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 32px;
    }

    .projects-section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
    }

    .create-project-btn,
    .join-project-btn {
      font-size: 14px;
      padding: 10px 18px;
      white-space: nowrap;
    }

    /* ======================================================
       FOOTER
    ====================================================== */

    @media (max-width: 768px) {
      footer {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }

      .projects-section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
      }

      .projects-section h2 {
        font-size: 20px;
        white-space: nowrap;
      }

      .create-project-btn,
      .join-project-btn {
        width: 100%;
        justify-content: center;
      }

      .projects-container {
        padding: 20px 16px;
        gap: 28px;
      }

      h1 {
        font-size: 2rem;
      }
    }

    /* ======================================================
       ESTILOS DEL MODAL
    ====================================================== */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
    }

    .hidden {
      display: none;
    }

    .main-card {
      width: 100%;
      max-width: 480px;
    }

    /* Reset del layout flex-row que estilos.css aplica a .main-card en mobile */
    .modal-overlay .main-card {
      display: block;
      width: 90%;
      max-width: 440px;
      text-align: center;
      padding: 28px 26px;
    }

    .modal-overlay .main-card p {
      color: var(--muted);
      font-size: 14px;
      margin: 10px 0 20px;
    }

    .modal-header {
      margin-bottom: 15px;
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 12px;
    }

    @media (max-width: 500px) {
      .modal-card {
        padding: 24px;
        width: 90%;
      }

      .modal-actions {
        flex-direction: column;
        gap: 10px;
      }
    }

    h1 {
      font-size: 3rem;
    }

    .project-options {
      position: absolute;
      top: 12px;
      right: 12px;
      cursor: pointer;
    }

    .project-options i {
      font-size: 1.2rem;
    }

    .options-menu {
      padding: 10px;
      overflow: visible;
      position: absolute;
      top: 130%;
      right: 0;
      border: 2px solid var(--magenta);
      border-radius: 6px;
      min-width: 140px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      z-index: 10;
    }

    .options-menu ul {
      list-style: none;
      padding: 3px 0;
      margin: 0;
    }

    .options-menu li {
      padding: 8px 12px;
    }

    /* Truncar texto largo en tarjetas de proyecto */
    .main-card h3 {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      max-width: 100%;
    }

    .main-card > a span,
    .main-card > a > span {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      word-break: break-all;
    }

    .logo img {
      height: 60px;
      width: auto;
    }

    .join-modal-hint {
      color: var(--muted);
      font-size: 14px;
      margin-top: 6px;
    }

    .join-modal-error {
      color: red;
      margin-bottom: 10px;
    }

    .codigo-input {
      text-transform: uppercase;
      letter-spacing: 2px;
    }
  </style>
</head>

<body class="magenta">
  <!-- HEADER -->
  <header>
    <div class="title-gradient logo pixel-logo"><img src="{{ asset('isotipo.png') }}" alt="">PIXEL</div>
    <div>
      <button class="theme-toggle-btn" onclick="window.toggleTheme()" title="Cambiar tema">
        <i class="fa-solid fa-circle-half-stroke"></i>
      </button>
      <a href="{{ route('perfil') }}" title="Editar perfil" class="header-perfil-link">
        <div class="user-avatar">
            {{ $usuario->initials }}
        </div>
      </a>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout">
          <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
        </button>
      </form>
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <div class="projects-container">
      <h1 class="title-gradient">Bienvenido, {{ $usuario->description }}</h1>
      <!-- PRODUCTOR -->
      <section class="projects-section">
        <div class="projects-section-header">
          <h2 class="title-gradient">Proyectos que produces</h2>

          <button class="btn-primary hover-lift create-project-btn">
            + Crear proyecto
          </button>
        </div>

        <div class="projects-grid">
          @forelse ($proyectos as $proyecto)
            <div class="main-card hover-lift relative" data-project-id="{{ $proyecto->id }}">
              <!-- Tres puntitos -->
              <div class="project-options" data-project-id="{{ $proyecto->id }}">
                <i class="fa-solid fa-ellipsis"></i>
                <div class="options-menu hidden">
                  <ul>
                    <li>
                      <button type="button" class="open-edit-modal"
                        data-project-id="{{ $proyecto->id }}"
                        data-url="{{ route('proyectos.update', $proyecto->id) }}">
                        Modificar
                      </button>
                    </li>
                    <li>
                      <button type="button" class="open-delete-modal"
                        data-project-id="{{ $proyecto->id }}"
                        data-url="{{ route('proyectos.destroy', $proyecto->id) }}">
                        Eliminar
                      </button>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Contenido principal -->
              <a href="{{ route('proyecto', $proyecto->id) }}">
                <h3>{{ $proyecto->name }}</h3>
                <span>{{ $proyecto->description }}</span>
              </a>
            </div>
          @empty
            <p>No tienes proyectos todavía.</p>
          @endforelse
        </div>
      </section>

      <!-- MIEMBRO -->
      <section class="projects-section">
        <div class="projects-section-header">
          <h2 class="title-gradient">Otros proyectos</h2>

          <button class="btn-primary hover-lift join-project-btn">
            + Unirse a un proyecto
          </button>
        </div>

        <div class="projects-grid">
          @forelse ($proyectosParticipando as $proy)
            <article class="main-card hover-lift">
              <a href="{{ route('proyecto', $proy->id) }}">
                <h3>{{ $proy->name }}</h3>
                <span>{{ $proy->description }}</span>
              </a>
            </article>
          @empty
            <p>Todavía no te has unido a ningún proyecto.</p>
          @endforelse
        </div>
      </section>

    </div>

  </main>

  <!-- FOOTER -->
  <footer>
    <span>© 2025 PIXEL. Todos los derechos reservados.</span>

    <div class="footer-links">
      <a href="#">Privacidad</a>
      <a href="#">Términos</a>
      <a href="#">Contacto</a>
    </div>
  </footer>

  <!-- MODAL CREAR PROYECTO -->
  <div class="modal-overlay hidden">
    <div class="main-card">

      <header class="modal-header">
        <h2 class="title-gradient">Crear nuevo proyecto</h2>
      </header>

      <form class="modal-form" method="POST" action="{{ route('proyectos.store') }}">
        @csrf
        <div class="form-group">
          <label for="project-name">Nombre del proyecto</label>
          <input type="text" id="name" name="name" required maxlength="80" placeholder="Máx. 80 caracteres">
        </div>

        <div class="form-group">
          <label for="project-desc">Descripción corta</label>
          <textarea id="project-desc" name="description" placeholder="Máx. 200 caracteres" rows="3" maxlength="200"></textarea>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-primary hover-lift">Crear proyecto</button>
          <button type="button" class="btn-secondary cancel-btn hover-lift">Cancelar</button>
        </div>
      </form>

    </div>
  </div>

  <!-- Modal Editar -->
<div class="modal-overlay hidden" id="edit-modal">
  <div class="main-card">
    <header class="modal-header">
      <h2 class="title-gradient">Modificar proyecto</h2>
    </header>

    <form id="edit-form" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="edit-name">Nombre del proyecto</label>
        <input type="text" id="edit-name" name="name" required maxlength="80">
      </div>

      <div class="form-group">
        <label for="edit-desc">Descripción</label>
        <textarea id="edit-desc" name="description" rows="3" maxlength="200"></textarea>
      </div>

      <div class="modal-actions">
        <button type="submit" class="btn-primary hover-lift">Guardar cambios</button>
        <button type="button" class="btn-secondary cancel-btn hover-lift" data-modal="edit-modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Eliminar -->
<div class="modal-overlay hidden" id="delete-modal">
  <div class="main-card">
    <header class="modal-header">
      <h2 class="title-gradient">Eliminar proyecto</h2>
    </header>

    <p>¿Estás seguro de que quieres eliminar este proyecto?</p>

    <form id="delete-form" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-actions">
        <button type="submit" class="btn-primary hover-lift">Sí, eliminar</button>
        <button type="button" class="btn-secondary cancel-btn hover-lift" data-modal="delete-modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Unirse a proyecto -->
<div class="modal-overlay hidden" id="join-modal">
  <div class="main-card">
    <header class="modal-header">
      <h2 class="title-gradient">Unirse a un proyecto</h2>
      <p class="join-modal-hint">Ingresa el código de invitación que recibiste.</p>
    </header>

    @error('codigo')
    <div class="join-modal-error">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('invitacion.unirse') }}">
      @csrf
      <div class="form-group">
        <label>Código de invitación</label>
        <input type="text" name="codigo" placeholder="PROY-XXXXXXXX" class="codigo-input" required>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn-primary hover-lift">Unirme</button>
        <button type="button" class="btn-secondary hover-lift cancel-btn" data-modal="join-modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>
</body>

</html>
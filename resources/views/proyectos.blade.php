<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>PIXEL | Proyectos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    .create-project-btn {
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

    .main-card {
      width: 100%;
      max-width: 480px;
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
  </style>
</head>

<body class="magenta">
  <!-- HEADER -->
  <header>
    <div class="title-gradient logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>
    <div>
      <div class="user-avatar">CM</div>
      <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer;">
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
            <a href="{{ route('proyecto') }}" class="main-card hover-lift">
              <h3>{{ $proyecto->name }}</h3>
              <span>{{ $proyecto->description }}</span>
            </a>
          @empty
            <p>No tienes proyectos todavía.</p>
          @endforelse
        </div>
      </section>

      <!-- MIEMBRO -->
      <section class="projects-section">
        <div class="projects-section-header">
          <h2 class="title-gradient">Otros proyectos</h2>
        </div>

        <div class="projects-grid">
          <article class="main-card hover-lift">
            <h3>Echoes</h3>
            <span>Diseño de niveles</span>
          </article>

          <article class="main-card hover-lift">
            <h3>Skybound</h3>
            <span>Programación gameplay</span>
          </article>

          <article class="main-card hover-lift">
            <h3>Void Signal</h3>
            <span>Arte conceptual</span>
          </article>
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
          <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label for="project-desc">Descripción corta</label>
          <textarea id="project-desc" name="description" placeholder="description" rows="3"></textarea>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-primary hover-lift">Crear proyecto</button>
          <button type="button" class="btn-secondary cancel-btn hover-lift">Cancelar</button>
        </div>
      </form>

    </div>
  </div>
</body>

</html>
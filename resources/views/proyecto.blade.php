<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | "Nombre del proyecto"</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite('resources/css/app.css')
</head>

<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">

    <!-- LOGO -->
    <div class="logo title-gradient"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>

    <!-- MENU -->
    <nav class="menu">

      <span class="menu-section">Menu</span>

      <!-- <div class="menu-item active hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
        <span>Dashboard</span>
      </div> -->

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

      <!-- <div class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-chart-area"></i></span>
        <span>Analytics</span>
      </div> -->

      <span class="menu-section">Sistema</span>

      <div class="menu-item hover-lift">
        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
        <span>Ajustes</span>
      </div>

    </nav>

    <!-- USER -->
    <div class="sidebar-user hover-lift">
      <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
      <div class="user-info">
        <strong>Alex Morgan</strong>
        <span class="title-gradient">Productor</span>
      </div>
      <div class="logout"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i></div>
    </div>

  </aside>


  <!-- MAIN -->
  <main>
    <div class="main-content">
      <!-- <div class="header">
      <input class="search" placeholder="Search magical realms..." />
      <button class="btn-primary hover-lift">Create Realm</button>
    </div> -->

      <h1 class="title-gradient">Tu Equipo</h1>
      <p class="subtitle">Datos del equipo</p>

      <!-- STATS -->
      <section class="stats">
        <div class="card hover-lift teal">
          <div class="stat-icon"><i class="fa-solid fa-people-group fa-lg"></i></div>

          <div class="stat-title">Miembros</div>
          <div class="stat-value">135</div>
        </div>

        <div class="card hover-lift magenta">
          <div class="stat-icon"><i class="fa-solid fa-shapes fa-lg"></i></div>

          <div class="stat-title">Departamentos</div>
          <div class="stat-value">8</div>
        </div>

        <div class="card hover-lift purple">
          <div class="stat-icon"><i class="fa-solid fa-folder-open fa-lg"></i></div>

          <div class="stat-title">Proyectos</div>
          <div class="stat-value">35</div>
        </div>

        <div class="card hover-lift blue">
          <div class="stat-icon"><i class="fa-solid fa-bars-progress fa-lg"></i></div>

          <div class="stat-title">Progreso</div>
          <div class="stat-value">94%</div>
        </div>
      </section>

      <!-- DEPARTMENTS -->
      <section class="departments">
        <h2 class="title-gradient">Departamentos</h2>

        <div class="department-grid">
          <div class="card main-card hover-lift teal">
            <div class="main-card-header">
              <div class="main-card-icon"><i class="fa-solid fa-code fa-lg"></i></div>
              <div>
                <div class="main-card-title">Desarrollo</div>
                <div class="main-card-subtitle">⚡ Code Wizards</div>
              </div>
            </div>

            <div class="main-card-stats">
              <div class="main-card-stat">
                <strong>24</strong>
                <span>Wizards</span>
              </div>
              <div class="main-card-stat">
                <strong>12</strong>
                <span>Quests</span>
              </div>
              <div class="main-card-stat">
                <strong>89%</strong>
                <span>Power</span>
              </div>
            </div>

            <div class="main-card-tags">
              <div class="main-card-tag">Frontend</div>
              <div class="main-card-tag">Backend</div>
              <div class="main-card-tag">DevOps</div>
            </div>
          </div>

          <div class="card main-card hover-lift magenta">
            <div class="main-card-header">
              <div class="main-card-icon"><i class="fa-solid fa-palette fa-lg"></i></div>
              <div>
                <div class="main-card-title">Arte</div>
                <div class="main-card-subtitle">⚡ Code Wizards</div>
              </div>
            </div>

            <div class="main-card-stats">
              <div class="main-card-stat">
                <strong>24</strong>
                <span>Wizards</span>
              </div>
              <div class="main-card-stat">
                <strong>12</strong>
                <span>Quests</span>
              </div>
              <div class="main-card-stat">
                <strong>89%</strong>
                <span>Power</span>
              </div>
            </div>

            <div class="main-card-tags">
              <div class="main-card-tag">Frontend</div>
              <div class="main-card-tag">Backend</div>
              <div class="main-card-tag">DevOps</div>
            </div>
          </div>

          <div class="card main-card hover-lift blue">
            <div class="main-card-header">
              <div class="main-card-icon"><i class="fa-solid fa-scroll fa-lg"></i></div>
              <div>
                <div class="main-card-title">Narrativa</div>
                <div class="main-card-subtitle">⚡ Code Wizards</div>
              </div>
            </div>

            <div class="main-card-stats">
              <div class="main-card-stat">
                <strong>24</strong>
                <span>Wizards</span>
              </div>
              <div class="main-card-stat">
                <strong>12</strong>
                <span>Quests</span>
              </div>
              <div class="main-card-stat">
                <strong>89%</strong>
                <span>Power</span>
              </div>
            </div>

            <div class="main-card-tags">
              <div class="main-card-tag">Frontend</div>
              <div class="main-card-tag">Backend</div>
              <div class="main-card-tag">DevOps</div>
            </div>
          </div>

          <div class="card main-card hover-lift green">
            <div class="main-card-header">
              <div class="main-card-icon"><i class="fa-solid fa-gamepad fa-lg"></i></div>
              <div>
                <div class="main-card-title">Diseño</div>
                <div class="main-card-subtitle">⚡ Code Wizards</div>
              </div>
            </div>

            <div class="main-card-stats">
              <div class="main-card-stat">
                <strong>24</strong>
                <span>Wizards</span>
              </div>
              <div class="main-card-stat">
                <strong>12</strong>
                <span>Quests</span>
              </div>
              <div class="main-card-stat">
                <strong>89%</strong>
                <span>Power</span>
              </div>
            </div>

            <div class="main-card-tags">
              <div class="main-card-tag">Frontend</div>
              <div class="main-card-tag">Backend</div>
              <div class="main-card-tag">DevOps</div>
            </div>
          </div>

          <div class="card main-card hover-lift purple">
            <div class="main-card-header">
              <div class="main-card-icon"><i class="fa-solid fa-music fa-lg"></i></div>
              <div>
                <div class="main-card-title">Audio</div>
                <div class="main-card-subtitle">⚡ Code Wizards</div>
              </div>
            </div>

            <div class="main-card-stats">
              <div class="main-card-stat">
                <strong>24</strong>
                <span>Wizards</span>
              </div>
              <div class="main-card-stat">
                <strong>12</strong>
                <span>Quests</span>
              </div>
              <div class="main-card-stat">
                <strong>89%</strong>
                <span>Power</span>
              </div>
            </div>

            <div class="main-card-tags">
              <div class="main-card-tag">Frontend</div>
              <div class="main-card-tag">Backend</div>
              <div class="main-card-tag">DevOps</div>
            </div>
          </div>

          <div class="card main-card hover-lift orange">
            <div class="main-card-header">
              <div class="main-card-icon"><i class="fa-solid fa-comments-dollar fa-lg"></i></div>
              <div>
                <div class="main-card-title">Marketing</div>
                <div class="main-card-subtitle">⚡ Code Wizards</div>
              </div>
            </div>

            <div class="main-card-stats">
              <div class="main-card-stat">
                <strong>24</strong>
                <span>Wizards</span>
              </div>
              <div class="main-card-stat">
                <strong>12</strong>
                <span>Quests</span>
              </div>
              <div class="main-card-stat">
                <strong>89%</strong>
                <span>Power</span>
              </div>
            </div>

            <div class="main-card-tags">
              <div class="main-card-tag">Frontend</div>
              <div class="main-card-tag">Backend</div>
              <div class="main-card-tag">DevOps</div>
            </div>
          </div>
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
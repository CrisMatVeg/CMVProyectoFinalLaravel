<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Tarea</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- ESTILOS ESPECÍFICOS DE LA VISTA -->
    <style>
        .task-metadata {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
        }

        .task-meta-item {
            font-size: 14px;
            color: var(--muted);
        }

        .task-meta-item strong {
            display: block;
            font-family: var(--font-title);
            color: var(--white);
            margin-bottom: 4px;
        }

        .task-users {
            margin-top: 32px;
        }

        .task-users h2 {
            font-size: var(--title-size-md);
            margin-bottom: 16px;
        }

        .user-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .status {
            margin-bottom: 25px;
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            background: var(--cardgrey);
            border: 1px solid var(--border);
            font-size: 14px;
        }

        .user-row .user-avatar {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }

        .user-row span {
            margin-left: auto;
            font-size: 12px;
            color: var(--muted);
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- LOGO -->
        <div class="logo pixel-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>

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
            <button class="theme-toggle-btn" onclick="window.toggleTheme()" title="Cambiar tema">
                <i class="fa-solid fa-circle-half-stroke"></i>
            </button>
            <div class="logout"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i></div>
        </div>

    </aside>

    <!-- MAIN -->
    <main>
        <div class="main-content">
            <!-- CABECERA TAREA -->
            <section class="task-header teal">
                <h1 class="title-gradient">Sistema de combate</h1>
                <p class="subtitle">
                    Implementación del sistema principal de combate y habilidades.
                </p>

                <div class="status wip">Work in Progress</div>

                <div class="task-metadata">
                    <div class="card">
                        <div class="task-meta-item">
                            <strong>Proyecto</strong>
                            Proyecto Aurora
                        </div>
                    </div>

                    <div class="card">
                        <div class="task-meta-item">
                            <strong>Departamento</strong>
                            Programación
                        </div>
                    </div>

                    <div class="card">
                        <div class="task-meta-item">
                            <strong>Tu rol</strong>
                            Programador
                        </div>
                    </div>
                </div>
            </section>

            <!-- USUARIOS -->
            <section class="task-users">
                <h2 class="title-gradient">Personas asignadas</h2>

                <div class="user-list">
                    <div class="user-row">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong>Ana López</strong>
                        <span>Programadora</span>
                    </div>

                    <div class="user-row">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong>Carlos Ruiz</strong>
                        <span>Gameplay</span>
                    </div>

                    <div class="user-row">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong>Lucía Martín</strong>
                        <span>IA</span>
                    </div>
                </div>
            </section>
        </div>
        <!-- FOOTER -->
        <footer class="magenta">
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
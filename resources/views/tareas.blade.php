<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Tareas</title>

    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- ESTILOS ESPECÍFICOS DE LA VISTA -->
    <style>
        .tasks-table {
            margin: 32px 0 32px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .tasks-row {
            display: grid;
            grid-template-columns: 2.5fr 1.2fr 1.2fr 1fr;
            padding: 14px 18px;
            background: var(--cardgrey);
            border-bottom: 1px solid var(--border);
            align-items: center;
            font-size: 14px;
        }

        .tasks-row.header {
            background: #1f2937;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            font-weight: bold;
        }

        .tasks-row:last-child {
            border-bottom: none;
        }

        .task-name {
            font-family: var(--font-title);
        }

        .task-project {
            font-size: 13px;
            color: var(--muted);
        }

        .task-department {
            font-size: 13px;
            color: var(--muted);
        }

        .tasks-row.hover:hover {
            background: var(--usercolor);
        }

        .task-filters {
            display: flex;
            gap: 24px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 180px;
        }

        .filter-group label {
            font-size: 12px;
            color: var(--muted);
            font-weight: bold;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
    </style>
</head>

<body class="teal">
    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- LOGO -->
        <div class="logo title-gradient"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>

        <!-- MENU -->
        <nav class="menu">

            <span class="menu-section">Menu</span>

            <!-- <div class="menu-item active hover">
        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
        <span>Dashboard</span>
      </div> -->

            <div class="menu-item hover active">
                <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
                <span>Equipo</span>
            </div>

            <div class="menu-item hover">
                <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
                <span>Proyectos</span>
            </div>

            <div class="menu-item hover">
                <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span>Calendario</span>
            </div>

            <!-- <div class="menu-item hover">
        <span class="menu-icon"><i class="fa-solid fa-chart-area"></i></span>
        <span>Analytics</span>
      </div> -->

            <span class="menu-section">Sistema</span>

            <div class="menu-item hover">
                <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                <span>Ajustes</span>
            </div>

        </nav>

        <!-- USER -->
        <div class="sidebar-user hover">
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
            <!-- CABECERA -->
            <section class="tasks-header">
                <h1 class="title-gradient">Tareas</h1>
                <p class="subtitle">Todas las tareas en las que participas</p>
            </section>

            <section class="task-filters card">
                <div class="filter-group">
                    <label>Estado</label>
                    <select>
                        <option value="">Todos</option>
                        <option value="todo">To Do</option>
                        <option value="wip">Work In Progress</option>
                        <option value="done">Finalizado</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Proyecto</label>
                    <select>
                        <option value="">Todos</option>
                        <option>Aurora</option>
                        <option>Neon Drift</option>
                        <option>Echoes</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Departamento</label>
                    <select>
                        <option value="">Todos</option>
                        <option>Programación</option>
                        <option>Arte</option>
                        <option>Sonido</option>
                    </select>
                </div>

                <div class="filter-action">
                    <button class="btn-primary hover-lift">
                        Filtrar
                    </button>
                </div>
            </section>

            <!-- TABLA DE TAREAS -->
            <section class="tasks-table">

                <div class="tasks-row header">
                    <div>Tarea</div>
                    <div>Proyecto</div>
                    <div>Departamento</div>
                    <div>Estado</div>
                </div>

                <div class="tasks-row hover">
                    <div class="task-name">Sistema de combate</div>
                    <div class="task-project">Proyecto Aurora</div>
                    <div class="task-department">Programación</div>
                    <div class="status wip">WIP</div>
                </div>

                <div class="tasks-row hover">
                    <div class="task-name">HUD principal</div>
                    <div class="task-project">Neon Drift</div>
                    <div class="task-department">UI / UX</div>
                    <div class="status todo">To Do</div>
                </div>

                <div class="tasks-row hover">
                    <div class="task-name">Música menú</div>
                    <div class="task-project">Echoes</div>
                    <div class="task-department">Sonido</div>
                    <div class="status done">Finalizado</div>
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
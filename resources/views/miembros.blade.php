<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Miembros del Departamento</title>

    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- ESTILOS ESPECÍFICOS DE LA VISTA -->
    <style>
        .members-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .subtitle{
            margin: 0;
        }

        .members-list {
            margin: 32px 0 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .member-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--cardgrey);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            gap: 12px;
        }

        .member-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .member-info .user-avatar {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            font-size: 14px;
        }

        .member-info strong {
            font-family: var(--font-title);
            font-size: 14px;
            color: var(--white);
        }

        .member-info span {
            font-size: 12px;
            color: var(--muted);
        }

        .member-actions {
            display: flex;
            gap: 10px;
        }

        .btn-secondary {
            padding: 6px 12px;
            font-size: 12px;
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
            <section class="members-header">
                <div>
                    <h1 class="title-gradient">Miembros</h1>
                    <p class="subtitle">Departamento de Desarrollo</p>
                </div>
                <button class="btn-primary hover-lift">
                    Añadir usuario
                </button>
            </section>

            <!-- LISTA DE MIEMBROS -->
            <section class="members-list">

                <div class="member-row">
                    <div class="member-info">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong>Ana López</strong>
                        <span>Programadora</span>
                    </div>
                    <!-- Acciones solo para productor -->
                    <div class="member-actions">
                        <button class="btn-secondary hover-lift">Expulsar</button>
                    </div>
                </div>

                <div class="member-row">
                    <div class="member-info">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong>Carlos Ruiz</strong>
                        <span>Gameplay</span>
                    </div>
                    <div class="member-actions">
                        <button class="btn-secondary hover-lift">Expulsar</button>
                    </div>
                </div>

                <div class="member-row">
                    <div class="member-info">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong>Lucía Martín</strong>
                        <span>IA</span>
                    </div>
                    <div class="member-actions">
                        <button class="btn-secondary hover-lift">Expulsar</button>
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
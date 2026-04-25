<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Gestión de proyectos creativos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')

    <style>
        /* Override layout global */
        body {
            height: auto;
            min-height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        .lp-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 68px;
            background: rgba(26, 26, 26, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .lp-nav-logo {
            font-family: var(--font-title);
            font-size: 26px;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .lp-nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .lp-nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .lp-nav-links a:hover { color: var(--white); }

        .lp-nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* ── HERO ── */
        .lp-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 130px 32px 100px;
            position: relative;
            overflow: hidden;
        }

        /* rejilla de fondo igual que main */
        .lp-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* glow central */
        .lp-hero::after {
            content: "";
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(ellipse at center,
                rgba(20,184,166,0.12) 0%,
                rgba(255,0,255,0.08) 40%,
                transparent 70%);
            pointer-events: none;
        }

        .lp-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--teal);
            background: rgba(20,184,166,0.1);
            border: 1px solid rgba(20,184,166,0.3);
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 56px;
            animation: fadeUp 0.6s ease-out forwards;
        }

        /* Titular principal: la propuesta de valor */
        .lp-hero-headline {
            font-family: var(--font-title);
            font-size: clamp(40px, 5.5vw, 72px);
            line-height: 1.15;
            color: var(--white);
            margin-bottom: 32px;
            max-width: 720px;
            animation: fadeUp 0.7s 0.1s ease-out both;
        }

        .lp-hero-headline .grad {
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lp-hero-sub {
            font-size: 17px;
            color: var(--muted);
            max-width: 480px;
            line-height: 1.75;
            margin-bottom: 60px;
            animation: fadeUp 0.7s 0.2s ease-out both;
        }

        .lp-hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 88px;
            animation: fadeUp 0.7s 0.3s ease-out both;
        }

        .lp-hero-actions .btn-primary {
            font-size: 16px;
            padding: 15px 36px;
        }

        .lp-hero-actions .btn-secondary {
            font-size: 16px;
            padding: 15px 32px;
        }

        /* ── MOCKUP ── */
        .lp-mockup {
            width: 100%;
            max-width: 900px;
            background: var(--cardgrey);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 40px 100px rgba(0,0,0,0.6),
                0 0 80px rgba(20,184,166,0.08),
                0 0 120px rgba(255,0,255,0.05);
            overflow: hidden;
            animation: fadeUp 0.8s 0.5s ease-out both;
            position: relative;
        }

        .lp-mockup-bar {
            height: 44px;
            background: #1a1a1a;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 8px;
        }

        .lp-mockup-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .lp-mockup-body {
            display: flex;
            height: 320px;
        }

        .lp-mockup-sidebar {
            width: 180px;
            min-width: 180px;
            background: #1e1e1e;
            border-right: 1px solid var(--border);
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .lp-mockup-logo {
            font-family: var(--font-title);
            font-size: 18px;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 16px;
            padding-left: 4px;
        }

        .lp-mockup-item {
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0 10px;
            font-size: 12px;
            color: var(--muted);
            font-family: var(--font-title);
        }

        .lp-mockup-item.active {
            background: linear-gradient(135deg, rgba(20,184,166,0.2), rgba(255,0,255,0.2));
            color: white;
            border: 1px solid rgba(20,184,166,0.25);
        }

        .lp-mockup-item i { opacity: 0.7; font-size: 11px; }

        .lp-mockup-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow: hidden;
        }

        .lp-mockup-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .lp-mockup-heading {
            font-family: var(--font-title);
            font-size: 16px;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lp-mockup-btn {
            font-size: 10px;
            padding: 5px 12px;
            border-radius: 8px;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            color: white;
            font-family: var(--font-title);
            font-weight: 600;
        }

        .lp-mockup-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .lp-mockup-stat {
            background: #1a1a1a;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px;
        }

        .lp-mockup-stat-val {
            font-family: var(--font-title);
            font-size: 20px;
            font-weight: bold;
        }

        .lp-mockup-stat-label {
            font-size: 10px;
            color: var(--muted);
            margin-top: 2px;
        }

        .lp-mockup-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .lp-mockup-card {
            background: #1a1a1a;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .lp-mockup-card-badge {
            font-size: 9px;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 999px;
            display: inline-block;
            width: fit-content;
        }

        .lp-mockup-card-title {
            font-size: 11px;
            font-weight: 600;
            font-family: var(--font-title);
        }

        .lp-mockup-card-bar {
            height: 3px;
            background: var(--border);
            border-radius: 999px;
            overflow: hidden;
        }

        .lp-mockup-card-fill {
            height: 100%;
            border-radius: 999px;
        }

        /* ── SECCIONES ── */
        .lp-section {
            padding: 96px 48px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .lp-section-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--teal);
            margin-bottom: 12px;
        }

        .lp-section-title {
            font-family: var(--font-title);
            font-size: clamp(28px, 4vw, 42px);
            color: var(--white);
            margin-bottom: 16px;
            line-height: 1.15;
        }

        .lp-section-title span {
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lp-section-sub {
            font-size: 16px;
            color: var(--muted);
            max-width: 560px;
            line-height: 1.7;
            margin-bottom: 56px;
        }

        /* ── FEATURES GRID ── */
        .lp-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .lp-feature-card {
            background: var(--cardgrey);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
            position: relative;
            overflow: hidden;
        }

        .lp-feature-card::before {
            content: "";
            position: absolute;
            inset: 0;
            padding: 1px;
            border-radius: inherit;
            background: linear-gradient(135deg, var(--teal), var(--magenta));
            mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .lp-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 30px rgba(20,184,166,0.08);
        }

        .lp-feature-card:hover::before { opacity: 1; }

        .lp-feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 20px;
            background: linear-gradient(135deg, rgba(20,184,166,0.15), rgba(255,0,255,0.15));
            color: var(--teal);
            border: 1px solid rgba(20,184,166,0.2);
        }

        .lp-feature-title {
            font-family: var(--font-title);
            font-size: 18px;
            color: var(--white);
        }

        .lp-feature-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── STEPS ── */
        .lp-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 32px;
            counter-reset: steps;
        }

        .lp-step {
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
        }

        .lp-step-number {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-family: var(--font-title);
            font-size: 22px;
            font-weight: bold;
            background: linear-gradient(135deg, var(--teal), var(--magenta));
            color: white;
            flex-shrink: 0;
        }

        .lp-step-title {
            font-family: var(--font-title);
            font-size: 18px;
            color: var(--white);
        }

        .lp-step-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── STATS BAND ── */
        .lp-stats-band {
            background: var(--cardgrey);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .lp-stats-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 56px 48px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 32px;
            text-align: center;
        }

        .lp-stat-val {
            font-family: var(--font-title);
            font-size: 48px;
            font-weight: bold;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 8px;
        }

        .lp-stat-label {
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }

        /* ── CTA FINAL ── */
        .lp-cta {
            position: relative;
            overflow: hidden;
            padding: 96px 48px;
            text-align: center;
        }

        .lp-cta::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .lp-cta-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 300px;
            background: radial-gradient(ellipse,
                rgba(20,184,166,0.15) 0%,
                rgba(255,0,255,0.1) 50%,
                transparent 70%);
            pointer-events: none;
        }

        .lp-cta-content {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 0 auto;
        }

        .lp-cta-title {
            font-family: var(--font-title);
            font-size: clamp(28px, 4vw, 46px);
            line-height: 1.15;
            margin-bottom: 16px;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lp-cta-sub {
            font-size: 16px;
            color: var(--muted);
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .lp-cta-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ── FOOTER ── */
        .lp-footer {
            background: var(--cardgrey);
            border-top: 1px solid var(--border);
            padding: 40px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .lp-footer-logo {
            font-family: var(--font-title);
            font-size: 22px;
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lp-footer-links {
            display: flex;
            gap: 24px;
            list-style: none;
        }

        .lp-footer-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .lp-footer-links a:hover { color: var(--white); }

        .lp-footer-copy {
            font-size: 13px;
            color: var(--muted2);
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Divider */
        .lp-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }
    </style>
</head>

<body>

    <!-- ══ NAVBAR ══ -->
    <nav class="lp-nav">
        <a class="lp-nav-logo" href="{{ route('home') }}">
            <i class="fa-solid fa-dice-d6"></i>PIXEL
        </a>
        <ul class="lp-nav-links">
            <li><a href="#features">Características</a></li>
            <li><a href="#como-funciona">Cómo funciona</a></li>
        </ul>
        <div class="lp-nav-actions">
            <a href="{{ route('login') }}">
                <button class="btn-secondary" style="padding: 9px 20px; font-size: 14px;">Iniciar sesión</button>
            </a>
            <a href="{{ route('registro') }}">
                <button class="btn-primary hover-lift" style="padding: 9px 20px; font-size: 14px;">Registrarse</button>
            </a>
        </div>
    </nav>

    <!-- ══ HERO ══ -->
    <section class="lp-hero">
        <div class="lp-badge"><i class="fa-solid fa-star"></i>Gestión de proyectos para equipos creativos</div>

        <h1 class="lp-hero-headline">
            Tu equipo. Tus proyectos.<br><span class="grad">Sin caos.</span>
        </h1>

        <p class="lp-hero-sub">
            Organiza tareas por departamento, asigna roles, mide el progreso y lleva tus proyectos de la idea al lanzamiento.
        </p>

        <div class="lp-hero-actions">
            <a href="{{ route('registro') }}">
                <button class="btn-primary hover-lift">
                    <i class="fa-solid fa-rocket" style="margin-right: 8px;"></i>Empezar gratis
                </button>
            </a>
            <a href="{{ route('login') }}">
                <button class="btn-secondary">
                    Iniciar sesión <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </button>
            </a>
        </div>

        <!-- Mockup CSS del dashboard -->
        <div class="lp-mockup">
            <div class="lp-mockup-bar">
                <div class="lp-mockup-dot" style="background:#ef4444;"></div>
                <div class="lp-mockup-dot" style="background:#f59e0b;"></div>
                <div class="lp-mockup-dot" style="background:#22c55e;"></div>
            </div>
            <div class="lp-mockup-body">
                <!-- sidebar mockup -->
                <div class="lp-mockup-sidebar">
                    <div class="lp-mockup-logo"><i class="fa-solid fa-dice-d6"></i> PIXEL</div>
                    <div class="lp-mockup-item active"><i class="fa-solid fa-house"></i> Proyecto</div>
                    <div class="lp-mockup-item"><i class="fa-solid fa-list-check"></i> Tareas</div>
                    <div class="lp-mockup-item"><i class="fa-solid fa-users"></i> Equipo</div>
                    <div class="lp-mockup-item"><i class="fa-solid fa-chart-line"></i> Progreso</div>
                    <div class="lp-mockup-item"><i class="fa-solid fa-gear"></i> Ajustes</div>
                </div>
                <!-- content mockup -->
                <div class="lp-mockup-content">
                    <div class="lp-mockup-title-row">
                        <span class="lp-mockup-heading">Mi Videojuego</span>
                        <span class="lp-mockup-btn"><i class="fa-solid fa-plus"></i> Nueva tarea</span>
                    </div>
                    <div class="lp-mockup-stats">
                        <div class="lp-mockup-stat">
                            <div class="lp-mockup-stat-val" style="color: var(--teal);">8</div>
                            <div class="lp-mockup-stat-label">Miembros</div>
                        </div>
                        <div class="lp-mockup-stat">
                            <div class="lp-mockup-stat-val" style="color: rgb(168,85,247);">24</div>
                            <div class="lp-mockup-stat-label">Tareas</div>
                        </div>
                        <div class="lp-mockup-stat">
                            <div class="lp-mockup-stat-val" style="color: var(--magenta);">67%</div>
                            <div class="lp-mockup-stat-label">Progreso</div>
                        </div>
                    </div>
                    <div class="lp-mockup-cards">
                        <div class="lp-mockup-card">
                            <span class="lp-mockup-card-badge" style="background:rgba(20,184,166,.15);color:var(--teal);border:1px solid rgba(20,184,166,.3);">Desarrollo</span>
                            <div class="lp-mockup-card-title">Sistema de combate v2</div>
                            <div class="lp-mockup-card-bar"><div class="lp-mockup-card-fill" style="width:75%;background:var(--teal);"></div></div>
                        </div>
                        <div class="lp-mockup-card">
                            <span class="lp-mockup-card-badge" style="background:rgba(255,0,255,.15);color:var(--magenta);border:1px solid rgba(255,0,255,.3);">Diseño</span>
                            <div class="lp-mockup-card-title">UI del menú principal</div>
                            <div class="lp-mockup-card-bar"><div class="lp-mockup-card-fill" style="width:40%;background:var(--magenta);"></div></div>
                        </div>
                        <div class="lp-mockup-card">
                            <span class="lp-mockup-card-badge" style="background:rgba(59,130,246,.15);color:rgb(59,130,246);border:1px solid rgba(59,130,246,.3);">Audio</span>
                            <div class="lp-mockup-card-title">Soundtrack nivel 1</div>
                            <div class="lp-mockup-card-bar"><div class="lp-mockup-card-fill" style="width:90%;background:rgb(59,130,246);"></div></div>
                        </div>
                        <div class="lp-mockup-card">
                            <span class="lp-mockup-card-badge" style="background:rgba(74,222,128,.15);color:rgb(74,222,128);border:1px solid rgba(74,222,128,.3);">Narrativa</span>
                            <div class="lp-mockup-card-title">Diálogos del tutorial</div>
                            <div class="lp-mockup-card-bar"><div class="lp-mockup-card-fill" style="width:55%;background:rgb(74,222,128);"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="lp-divider"></div>

    <!-- ══ STATS BAND ══ -->
    <div class="lp-stats-band">
        <div class="lp-stats-inner">
            <div>
                <div class="lp-stat-val">6</div>
                <div class="lp-stat-label">Departamentos por proyecto</div>
            </div>
            <div>
                <div class="lp-stat-val">∞</div>
                <div class="lp-stat-label">Tareas por tipo</div>
            </div>
            <div>
                <div class="lp-stat-val">100%</div>
                <div class="lp-stat-label">Control del progreso</div>
            </div>
            <div>
                <div class="lp-stat-val">0</div>
                <div class="lp-stat-label">Caos en el equipo</div>
            </div>
        </div>
    </div>

    <!-- ══ FEATURES ══ -->
    <section class="lp-section" id="features">
        <p class="lp-section-label"><i class="fa-solid fa-sparkles" style="margin-right:6px;"></i>Características</p>
        <h2 class="lp-section-title">Todo lo que necesita <span>tu equipo creativo</span></h2>
        <p class="lp-section-sub">
            Diseñado para estudios de videojuegos, agencias y equipos creativos que necesitan estructura sin perder agilidad.
        </p>

        <div class="lp-features-grid">
            <div class="lp-feature-card">
                <div class="lp-feature-icon" style="color:var(--teal);"><i class="fa-solid fa-list-check"></i></div>
                <div class="lp-feature-title">Gestión de tareas</div>
                <p class="lp-feature-desc">Crea, asigna y prioriza tareas dentro de cada departamento. Visualiza el estado en tiempo real.</p>
            </div>
            <div class="lp-feature-card">
                <div class="lp-feature-icon" style="color:var(--magenta);"><i class="fa-solid fa-folder-tree"></i></div>
                <div class="lp-feature-title">Departamentos especializados</div>
                <p class="lp-feature-desc">Desarrolla, diseña, compón, narra, ilustra y comunica — cada área con su propio espacio de trabajo.</p>
            </div>
            <div class="lp-feature-card">
                <div class="lp-feature-icon" style="color:rgb(168,85,247);"><i class="fa-solid fa-users"></i></div>
                <div class="lp-feature-title">Roles por proyecto</div>
                <p class="lp-feature-desc">Asigna colaboradores según su disciplina. Solo los perfiles adecuados acceden a cada tipo de tarea.</p>
            </div>
            <div class="lp-feature-card">
                <div class="lp-feature-icon" style="color:rgb(59,130,246);"><i class="fa-solid fa-chart-line"></i></div>
                <div class="lp-feature-title">Progreso en tiempo real</div>
                <p class="lp-feature-desc">Métricas claras por proyecto y departamento. Siempre sabes en qué punto está el equipo.</p>
            </div>
            <div class="lp-feature-card">
                <div class="lp-feature-icon" style="color:rgb(251,146,60);"><i class="fa-solid fa-hourglass-half"></i></div>
                <div class="lp-feature-title">Estimación de horas</div>
                <p class="lp-feature-desc">Registra horas estimadas y reales por tarea. Controla la carga de trabajo del equipo.</p>
            </div>
            <div class="lp-feature-card">
                <div class="lp-feature-icon" style="color:rgb(74,222,128);"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="lp-feature-title">Seguridad y control</div>
                <p class="lp-feature-desc">Solo tú controlas quién entra a tu proyecto. Confirmación antes de cualquier acción destructiva.</p>
            </div>
        </div>
    </section>

    <div class="lp-divider"></div>

    <!-- ══ CÓMO FUNCIONA ══ -->
    <section class="lp-section" id="como-funciona">
        <p class="lp-section-label"><i class="fa-solid fa-map" style="margin-right:6px;"></i>Cómo funciona</p>
        <h2 class="lp-section-title">De la idea al <span>lanzamiento</span></h2>
        <p class="lp-section-sub">Tres pasos para tener tu proyecto bajo control desde el primer día.</p>

        <div class="lp-steps">
            <div class="lp-step">
                <div class="lp-step-number">1</div>
                <div class="lp-step-title">Crea tu proyecto</div>
                <p class="lp-step-desc">Registra tu proyecto, define su nombre y descripción. El panel de control se genera automáticamente con los 6 departamentos.</p>
            </div>
            <div class="lp-step">
                <div class="lp-step-number">2</div>
                <div class="lp-step-title">Organiza tareas por área</div>
                <p class="lp-step-desc">Entra a cada departamento y crea las tareas necesarias. Asigna estado, horas estimadas y colaboradores con el rol adecuado.</p>
            </div>
            <div class="lp-step">
                <div class="lp-step-number">3</div>
                <div class="lp-step-title">Sigue el progreso</div>
                <p class="lp-step-desc">Desde el panel del proyecto, visualiza el avance global y por departamento. Actualiza tareas conforme el equipo avanza.</p>
            </div>
        </div>
    </section>

    <div class="lp-divider"></div>

    <!-- ══ CTA FINAL ══ -->
    <section class="lp-cta">
        <div class="lp-cta-glow"></div>
        <div class="lp-cta-content">
            <h2 class="lp-cta-title">¿Listo para organizar tu equipo?</h2>
            <p class="lp-cta-sub">Únete y lleva tus proyectos creativos al siguiente nivel. Gratis, sin complicaciones.</p>
            <div class="lp-cta-actions">
                <a href="{{ route('registro') }}">
                    <button class="btn-primary hover-lift" style="font-size:16px; padding:14px 36px;">
                        <i class="fa-solid fa-rocket" style="margin-right: 8px;"></i>Crear cuenta gratis
                    </button>
                </a>
                <a href="{{ route('login') }}">
                    <button class="btn-secondary" style="font-size:16px; padding:14px 32px;">Ya tengo cuenta</button>
                </a>
            </div>
        </div>
    </section>

    <!-- ══ FOOTER ══ -->
    <footer class="lp-footer">
        <div class="lp-footer-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</div>
        <ul class="lp-footer-links">
            <li><a href="#">Privacidad</a></li>
            <li><a href="#">Términos</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
        <span class="lp-footer-copy">© 2025 PIXEL. Todos los derechos reservados.</span>
    </footer>

</body>

</html>

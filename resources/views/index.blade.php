<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Gestión de proyectos creativos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        /* ── LAYOUT GENERAL ── */
        html, body { height: 100%; overflow: hidden; }
        body { flex-direction: column !important; display: flex !important; }

        /* ── NAV — chrome elevado ── */
        .lp-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 64px;
            background: var(--bg-chrome);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-chrome);
            position: relative;
            z-index: 5;
            flex-shrink: 0;
        }

        .lp-nav-logo {
            font-size: 20px;
        }

        .lp-nav-links {
            display: flex;
            gap: 4px;
            list-style: none;
        }

        .lp-nav-link {
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--muted);
            font-family: var(--font-main);
            font-size: 13px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .lp-nav-link:hover   { color: var(--white); background: rgba(255,255,255,.04); }
        .lp-nav-link.is-active { color: var(--white); background: rgba(255,255,255,.06); }

        .lp-nav-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .lp-theme-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            cursor: pointer;
            display: grid;
            place-items: center;
            font-size: 14px;
            transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .lp-theme-btn:hover {
            background: rgba(255,255,255,.04);
            color: var(--teal);
            border-color: var(--border-strong);
        }

        .btn-nav {
            font-family: var(--font-title);
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: filter 0.2s ease, transform 0.2s ease,
                        background 0.2s ease, border-color 0.2s ease;
        }

        .btn-nav-secondary {
            background: transparent;
            border: 1px solid var(--border-strong);
            color: var(--white);
        }

        .btn-nav-secondary:hover {
            background: rgba(255,255,255,.04);
            border-color: var(--border-focus);
        }

        .btn-nav-primary {
            background: var(--teal);
            border: 1px solid transparent;
            color: #111;
        }

        .btn-nav-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }

        /* ── CARRUSEL HORIZONTAL ── */
        .lp-carousel {
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        /* Cuadrícula de fondo con máscara */
        .lp-carousel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--grid-line) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(ellipse 90% 80% at 50% 45%, #000 50%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 45%, #000 50%, transparent 100%);
        }

        .lp-track {
            display: flex;
            height: 100%;
            transition: transform 0.65s cubic-bezier(0.65, 0.05, 0.36, 1);
            will-change: transform;
            position: relative;
            z-index: 1;
        }

        .lp-slide {
            flex: 0 0 100%;
            height: 100%;
            padding: 56px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            position: relative;
        }

        /* ── Flechas laterales ── */
        .lp-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bg-chrome);
            border: 1px solid var(--border-strong);
            color: var(--white);
            cursor: pointer;
            display: grid;
            place-items: center;
            font-size: 14px;
            box-shadow: var(--shadow-card);
            transition: background 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
            z-index: 4;
        }
        .lp-arrow:hover:not(:disabled) {
            background: var(--bg-card-hi);
            transform: translateY(-50%) scale(1.05);
        }
        .lp-arrow:disabled { opacity: 0.2; cursor: default; }
        .lp-arrow-prev { left: 20px; }
        .lp-arrow-next { right: 20px; }

        /* ── FOOTER BAR — chrome elevado ── */
        .lp-footer-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 32px;
            background: var(--bg-chrome);
            border-top: 1px solid var(--border);
            box-shadow: 0 -1px 0 rgba(255,255,255,.02), 0 -4px 16px -8px rgba(0,0,0,.4);
            flex-shrink: 0;
        }


        .lp-counter {
            font-family: var(--font-title);
            display: flex;
            gap: 4px;
            align-items: baseline;
            color: var(--muted);
            font-size: 13px;
            font-variant-numeric: tabular-nums;
        }

        .lp-counter-num { color: var(--white); font-size: 18px; font-weight: 600; }
        .lp-counter-sep { opacity: .4; }

        /* ── EYEBROW / SLIDE HEADS ── */
        .lp-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--teal);
            margin-bottom: 14px;
        }

        .lp-slide-title {
            font-family: var(--font-title);
            font-size: clamp(32px, 4vw, 48px);
            line-height: 1.1;
            color: var(--white);
            font-weight: 600;
        }

        .grad-soft {
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lp-slide-head  { margin-bottom: 40px; max-width: 720px; }
        .lp-slide-head-center { text-align: center; margin: 0 auto 40px; }

        /* ── SLIDE HERO ── */
        .lp-slide-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
            padding: 56px 120px;
        }

        /* Glows ambientales */
        .lp-slide-hero::before,
        .lp-slide-cta::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 30%, rgba(20,184,166,.18), transparent 45%),
                radial-gradient(circle at 85% 70%, rgba(196,77,186,.15), transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        [data-theme="light"] .lp-slide-hero::before,
        [data-theme="light"] .lp-slide-cta::before {
            background:
                radial-gradient(circle at 15% 30%, rgba(20,184,166,.14), transparent 45%),
                radial-gradient(circle at 85% 70%, rgba(196,77,186,.10), transparent 50%);
        }

        .lp-slide > * { position: relative; z-index: 1; }

        .lp-hero-left  { max-width: 480px; }
        .lp-hero-right { display: flex; align-items: center; justify-content: flex-end; }

        .lp-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--teal);
            background: rgba(20,184,166,.10);
            border: 1px solid rgba(20,184,166,.25);
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 28px;
        }

        .lp-hero-headline {
            font-family: var(--font-title);
            font-size: clamp(40px, 4.5vw, 60px);
            line-height: 1.05;
            color: var(--white);
            font-weight: 600;
            margin-bottom: 24px;
        }

        .lp-hero-headline .grad {
            background: linear-gradient(90deg, var(--teal), var(--magenta));
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lp-hero-sub {
            font-size: 16px;
            line-height: 1.6;
            color: var(--muted);
            margin-bottom: 32px;
        }

        .lp-hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        .btn-hero {
            font-family: var(--font-title);
            padding: 12px 24px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: filter 0.2s ease, transform 0.2s ease,
                        background 0.2s ease, border-color 0.2s ease;
        }

        .btn-hero-primary {
            background: var(--teal);
            color: #111;
            border: 1px solid transparent;
        }

        .btn-hero-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }

        .btn-hero-secondary {
            background: transparent;
            color: var(--white);
            border: 1px solid var(--border-strong);
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,.04);
            border-color: var(--border-focus);
        }

        /* ── MINI MOCKUP ── */
        .mm-frame {
            width: 100%;
            max-width: 520px;
            background: var(--bg-chrome);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.02);
            overflow: hidden;
        }

        .mm-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 14px;
            background: rgba(0,0,0,.25);
            border-bottom: 1px solid var(--border);
        }

        .mm-dot { width: 10px; height: 10px; border-radius: 50%; }
        .mm-dot-red    { background: #ef4444; }
        .mm-dot-yellow { background: #f59e0b; }
        .mm-dot-green  { background: #22c55e; }

        .mm-url {
            margin-left: 14px;
            font-size: 11px;
            color: var(--muted2);
            font-family: ui-monospace, monospace;
        }

        .mm-body { display: flex; height: 300px; background: var(--bg-base); }

        .mm-side {
            width: 140px;
            background: var(--bg-chrome);
            padding: 12px 8px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            border-right: 1px solid var(--border);
        }

        .mm-logo {
            font-family: var(--font-title);
            font-size: 13px;
            color: var(--white);
            font-weight: 600;
            padding: 4px 10px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mm-logo img { height: 1.3em; width: auto; }

        .mm-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            color: var(--muted);
            font-family: var(--font-title);
            padding: 6px 10px;
            border-radius: 6px;
        }

        .mm-item.is-active {
            background: rgba(20,184,166,.12);
            color: var(--teal);
            border: 1px solid rgba(20,184,166,.22);
        }

        .mm-content {
            flex: 1;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-width: 0;
        }

        .mm-title-row { display: flex; justify-content: space-between; align-items: center; }
        .mm-heading { font-family: var(--font-title); font-size: 13px; font-weight: 600; color: var(--white); }

        .mm-btn {
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 6px;
            background: var(--teal);
            color: #111;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .mm-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }

        .mm-stat {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px;
        }

        .mm-stat-v { font-family: var(--font-title); font-size: 16px; font-weight: 600; color: var(--white); }
        .mm-stat-l { font-size: 9px; color: var(--muted); margin-top: 2px; }

        .mm-cards { display: grid; grid-template-columns: repeat(2, 1fr); gap: 7px; }

        .mm-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 9px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .mm-badge {
            font-size: 8px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 999px;
            align-self: flex-start;
        }

        .mm-card-t { font-size: 10px; font-weight: 600; color: var(--white); font-family: var(--font-title); }
        .mm-bar-prog { height: 3px; background: rgba(255,255,255,.06); border-radius: 999px; overflow: hidden; }
        .mm-bar-fill { height: 100%; border-radius: 999px; }

        .mm-teal .mm-badge   { color: rgb(20,184,166);   background: rgba(20,184,166,.10); }
        .mm-teal .mm-bar-fill { background: rgb(20,184,166); }
        .mm-mag .mm-badge    { color: rgb(196,77,186);    background: rgba(196,77,186,.10); }
        .mm-mag .mm-bar-fill { background: rgb(196,77,186); }
        .mm-blue .mm-badge   { color: rgb(59,130,246);    background: rgba(59,130,246,.10); }
        .mm-blue .mm-bar-fill { background: rgb(59,130,246); }
        .mm-green .mm-badge  { color: rgb(74,222,128);    background: rgba(74,222,128,.10); }
        .mm-green .mm-bar-fill { background: rgb(74,222,128); }

        /* ── SLIDE FEATURES ── */
        .lp-feat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .lp-feat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow: hidden;
            position: relative;
            transition: background 0.25s ease, border-color 0.25s ease,
                        transform 0.25s ease, box-shadow 0.25s ease;
        }

        /* Halo de color en hover */
        .lp-feat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 0%, var(--feat-soft, transparent), transparent 60%);
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
        }

        .lp-feat-card:hover::before { opacity: 1; }
        .lp-feat-card > * { position: relative; }

        .lp-feat-card:hover {
            background: var(--bg-card-hi);
            border-color: var(--feat-border, var(--border-strong));
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -16px rgba(0,0,0,.5),
                        0 8px 24px -12px var(--feat-glow, transparent);
        }

        /* Por-color de tarjetas de características */
        .lp-feat-card.fc-teal    { --feat-color: rgb(20,184,166);  --feat-soft: rgba(20,184,166,.10);  --feat-border: rgba(20,184,166,.22);  --feat-glow: rgba(20,184,166,.35); }
        .lp-feat-card.fc-magenta { --feat-color: rgb(196,77,186);  --feat-soft: rgba(196,77,186,.10);  --feat-border: rgba(196,77,186,.22);  --feat-glow: rgba(196,77,186,.30); }
        .lp-feat-card.fc-violet  { --feat-color: rgb(168,85,247);  --feat-soft: rgba(168,85,247,.10);  --feat-border: rgba(168,85,247,.22);  --feat-glow: rgba(168,85,247,.32); }
        .lp-feat-card.fc-blue    { --feat-color: rgb(59,130,246);  --feat-soft: rgba(59,130,246,.10);  --feat-border: rgba(59,130,246,.22);  --feat-glow: rgba(59,130,246,.32); }
        .lp-feat-card.fc-amber   { --feat-color: rgb(245,158,11);  --feat-soft: rgba(245,158,11,.10);  --feat-border: rgba(245,158,11,.22);  --feat-glow: rgba(245,158,11,.35); }
        .lp-feat-card.fc-green   { --feat-color: rgb(74,222,128);  --feat-soft: rgba(74,222,128,.10);  --feat-border: rgba(74,222,128,.22);  --feat-glow: rgba(74,222,128,.32); }

        .lp-feat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 16px;
            background: var(--feat-soft, rgba(255,255,255,.05));
            color: var(--feat-color, var(--white));
            border: 1px solid var(--feat-border, var(--border));
        }

        .lp-feat-t { font-family: var(--font-title); font-size: 16px; color: var(--white); font-weight: 600; }
        .lp-feat-d { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* ── SLIDE STEPS ── */
        .lp-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .lp-step {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .lp-step::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 0% 0%, rgba(20,184,166,.08), transparent 55%);
            pointer-events: none;
        }

        .lp-step > * { position: relative; }

        .lp-step:hover {
            transform: translateY(-3px);
            border-color: rgba(20,184,166,.25);
            box-shadow: 0 12px 28px -14px rgba(20,184,166,.3);
        }

        .lp-step-num {
            font-family: var(--font-title);
            font-size: 36px;
            font-weight: 700;
            color: var(--teal);
            line-height: 1;
            margin-bottom: 8px;
        }

        .lp-step-t { font-family: var(--font-title); font-size: 18px; color: var(--white); font-weight: 600; }
        .lp-step-d { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* ── SLIDE NUMBERS ── */
        .lp-numbers {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1080px;
            margin: 0 auto;
        }

        .lp-number {
            position: relative;
            text-align: center;
            padding: 36px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .lp-number::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(20,184,166,.10), transparent 60%);
            pointer-events: none;
        }

        .lp-number > * { position: relative; }

        .lp-number:hover {
            transform: translateY(-3px);
            border-color: rgba(20,184,166,.25);
            box-shadow: 0 12px 30px -14px rgba(20,184,166,.3);
        }

        .lp-number-v {
            font-family: var(--font-title);
            font-size: clamp(44px, 5vw, 64px);
            font-weight: 700;
            background: linear-gradient(180deg, var(--white), var(--teal));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
            line-height: 1;
        }

        .lp-number-l { font-size: 13px; color: var(--muted); }

        /* ── SLIDE CTA ── */
        .lp-slide-cta {
            align-items: center;
            justify-content: center;
        }

        .lp-cta-card {
            position: relative;
            max-width: 600px;
            width: 100%;
            text-align: center;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 56px 48px;
            box-shadow: var(--shadow-card-hi),
                        0 30px 80px -40px rgba(20,184,166,.30),
                        0 30px 80px -40px rgba(196,77,186,.25);
            overflow: hidden;
        }

        /* Borde de degradado */
        .lp-cta-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(20,184,166,.4), transparent 35%, transparent 65%, rgba(196,77,186,.4));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                    mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
                    mask-composite: exclude;
            pointer-events: none;
        }

        .lp-cta-card > * { position: relative; }

        .lp-cta-title {
            font-family: var(--font-title);
            font-size: clamp(28px, 3.5vw, 42px);
            line-height: 1.1;
            color: var(--white);
            font-weight: 600;
            margin: 12px 0 16px;
        }

        .lp-cta-sub { font-size: 15px; color: var(--muted); margin-bottom: 28px; }
        .lp-cta-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .lp-cta-logo { display: block; width: 140px; height: auto; margin: 0 auto 24px; filter: drop-shadow(0 0 24px rgba(20,184,166,.5)) drop-shadow(0 0 48px rgba(196,77,186,.3)); }

        /* ── LIGHT THEME OVERRIDES ── */
        [data-theme="light"] .btn-nav-primary { color: #fff; }
        [data-theme="light"] .btn-hero-primary { color: #fff; }
        [data-theme="light"] .mm-btn { color: #fff; }
        [data-theme="light"] .mm-item.is-active { color: rgb(20,184,166); }
        [data-theme="light"] .lp-feat-card:hover,
        [data-theme="light"] .lp-step:hover,
        [data-theme="light"] .lp-number:hover { background: var(--bg-card); }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav class="lp-nav">
        <a class="lp-nav-logo pixel-logo" href="{{ route('home') }}">
            <img src="{{ asset('isotipo.png') }}" alt="" style="height:60px;width:auto;">
            <span>PIXEL</span>
        </a>

        <ul class="lp-nav-links">
            <li><button class="lp-nav-link is-active" data-index="0">Bienvenida</button></li>
            <li><button class="lp-nav-link" data-index="1">Características</button></li>
            <li><button class="lp-nav-link" data-index="2">Cómo funciona</button></li>
            <li><button class="lp-nav-link" data-index="3">En números</button></li>
            <li><button class="lp-nav-link" data-index="4">Empezar</button></li>
        </ul>

        <div class="lp-nav-actions">
            <button class="lp-theme-btn" id="lp-theme-btn" aria-label="Cambiar tema">
                <i class="fa-solid fa-circle-half-stroke"></i>
            </button>
            <a href="{{ route('login') }}">
                <button class="btn-nav btn-nav-primary">Acceder</button>
            </a>
        </div>
    </nav>

    <!-- CARRUSEL -->
    <div class="lp-carousel">
        <div class="lp-track" id="lp-track">

            <!-- SLIDE 1: BIENVENIDA -->
            <section class="lp-slide lp-slide-hero">
                <div class="lp-hero-left">
                    <div class="lp-badge">
                        <i class="fa-solid fa-sparkles"></i>
                        Gestión de proyectos creativos
                    </div>
                    <h1 class="lp-hero-headline">
                        Tu equipo.<br>
                        Tus proyectos.<br>
                        <span class="grad">Sin caos.</span>
                    </h1>
                    <p class="lp-hero-sub">
                        Organiza tareas por departamento, asigna roles, mide el progreso y lleva
                        tus proyectos de la idea al lanzamiento.
                    </p>
                    <div class="lp-hero-actions">
                        <a href="{{ route('registro') }}">
                            <button class="btn-hero btn-hero-primary">
                                <i class="fa-solid fa-rocket"></i> Empezar gratis
                            </button>
                        </a>
                        <a href="{{ route('login') }}">
                            <button class="btn-hero btn-hero-secondary">
                                Ver demo <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </a>
                    </div>
                </div>

                <div class="lp-hero-right">
                    <div class="mm-frame">
                        <div class="mm-bar">
                            <span class="mm-dot mm-dot-red"></span>
                            <span class="mm-dot mm-dot-yellow"></span>
                            <span class="mm-dot mm-dot-green"></span>
                            <span class="mm-url">pixel.app / mi-videojuego</span>
                        </div>
                        <div class="mm-body">
                            <aside class="mm-side">
                                <div class="mm-logo"><img src="{{ asset('isotipo.png') }}" alt=""> PIXEL</div>
                                <div class="mm-item is-active"><i class="fa-solid fa-house"></i> Proyecto</div>
                                <div class="mm-item"><i class="fa-solid fa-list-check"></i> Tareas</div>
                                <div class="mm-item"><i class="fa-solid fa-users"></i> Equipo</div>
                                <div class="mm-item"><i class="fa-solid fa-chart-line"></i> Progreso</div>
                            </aside>
                            <div class="mm-content">
                                <div class="mm-title-row">
                                    <div class="mm-heading">Mi Videojuego</div>
                                    <div class="mm-btn"><i class="fa-solid fa-plus"></i> Nueva tarea</div>
                                </div>
                                <div class="mm-stats">
                                    <div class="mm-stat"><div class="mm-stat-v">8</div><div class="mm-stat-l">Miembros</div></div>
                                    <div class="mm-stat"><div class="mm-stat-v">24</div><div class="mm-stat-l">Tareas</div></div>
                                    <div class="mm-stat"><div class="mm-stat-v">67%</div><div class="mm-stat-l">Progreso</div></div>
                                </div>
                                <div class="mm-cards">
                                    <div class="mm-card mm-teal">
                                        <span class="mm-badge">Desarrollo</span>
                                        <div class="mm-card-t">Sistema de combate</div>
                                        <div class="mm-bar-prog"><div class="mm-bar-fill" style="width:75%"></div></div>
                                    </div>
                                    <div class="mm-card mm-mag">
                                        <span class="mm-badge">Diseño</span>
                                        <div class="mm-card-t">UI del menú</div>
                                        <div class="mm-bar-prog"><div class="mm-bar-fill" style="width:40%"></div></div>
                                    </div>
                                    <div class="mm-card mm-blue">
                                        <span class="mm-badge">Audio</span>
                                        <div class="mm-card-t">Soundtrack nivel 1</div>
                                        <div class="mm-bar-prog"><div class="mm-bar-fill" style="width:90%"></div></div>
                                    </div>
                                    <div class="mm-card mm-green">
                                        <span class="mm-badge">Narrativa</span>
                                        <div class="mm-card-t">Diálogos tutorial</div>
                                        <div class="mm-bar-prog"><div class="mm-bar-fill" style="width:55%"></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SLIDE 2: CARACTERÍSTICAS -->
            <section class="lp-slide">
                <div class="lp-slide-head">
                    <p class="lp-eyebrow">Características</p>
                    <h2 class="lp-slide-title">
                        Todo lo que necesita <span class="grad-soft">tu equipo creativo</span>
                    </h2>
                </div>
                <div class="lp-feat-grid">
                    <article class="lp-feat-card fc-teal">
                        <div class="lp-feat-icon"><i class="fa-solid fa-list-check"></i></div>
                        <h3 class="lp-feat-t">Gestión de tareas</h3>
                        <p class="lp-feat-d">Crea, asigna y prioriza tareas dentro de cada departamento. Visualiza el estado en tiempo real.</p>
                    </article>
                    <article class="lp-feat-card fc-magenta">
                        <div class="lp-feat-icon"><i class="fa-solid fa-folder-tree"></i></div>
                        <h3 class="lp-feat-t">Departamentos</h3>
                        <p class="lp-feat-d">Desarrollo, diseño, audio, narrativa, arte y comunicación, cada área con su espacio.</p>
                    </article>
                    <article class="lp-feat-card fc-violet">
                        <div class="lp-feat-icon"><i class="fa-solid fa-users"></i></div>
                        <h3 class="lp-feat-t">Roles por proyecto</h3>
                        <p class="lp-feat-d">Solo los perfiles adecuados acceden a cada tipo de tarea.</p>
                    </article>
                    <article class="lp-feat-card fc-blue">
                        <div class="lp-feat-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h3 class="lp-feat-t">Progreso real</h3>
                        <p class="lp-feat-d">Métricas claras por proyecto y departamento. Siempre sabes en qué punto está el equipo.</p>
                    </article>
                    <article class="lp-feat-card fc-amber">
                        <div class="lp-feat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                        <h3 class="lp-feat-t">Estimación de horas</h3>
                        <p class="lp-feat-d">Registra horas estimadas y reales por tarea. Controla la carga del equipo.</p>
                    </article>
                    <article class="lp-feat-card fc-green">
                        <div class="lp-feat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h3 class="lp-feat-t">Seguridad y control</h3>
                        <p class="lp-feat-d">Tú controlas quién entra. Confirmación antes de cualquier acción crítica.</p>
                    </article>
                </div>
            </section>

            <!-- SLIDE 3: CÓMO FUNCIONA -->
            <section class="lp-slide">
                <div class="lp-slide-head">
                    <p class="lp-eyebrow">Cómo funciona</p>
                    <h2 class="lp-slide-title">
                        De la idea al <span class="grad-soft">lanzamiento</span>
                    </h2>
                </div>
                <div class="lp-steps">
                    <div class="lp-step">
                        <div class="lp-step-num">01</div>
                        <h3 class="lp-step-t">Crea tu proyecto</h3>
                        <p class="lp-step-d">Registra el proyecto y se generan los 6 departamentos automáticamente con su panel de control.</p>
                    </div>
                    <div class="lp-step">
                        <div class="lp-step-num">02</div>
                        <h3 class="lp-step-t">Organiza tareas</h3>
                        <p class="lp-step-d">Entra a cada departamento, crea tareas, asigna colaboradores con el rol adecuado y registra horas.</p>
                    </div>
                    <div class="lp-step">
                        <div class="lp-step-num">03</div>
                        <h3 class="lp-step-t">Sigue el progreso</h3>
                        <p class="lp-step-d">Desde el panel del proyecto, visualiza el avance global y por departamento en tiempo real.</p>
                    </div>
                </div>
            </section>

            <!-- SLIDE 4: EN NÚMEROS -->
            <section class="lp-slide">
                <div class="lp-slide-head lp-slide-head-center">
                    <p class="lp-eyebrow">En números</p>
                    <h2 class="lp-slide-title">
                        Estructura sin <span class="grad-soft">perder agilidad</span>
                    </h2>
                </div>
                <div class="lp-numbers">
                    <div class="lp-number">
                        <div class="lp-number-v">6</div>
                        <div class="lp-number-l">Departamentos por proyecto</div>
                    </div>
                    <div class="lp-number">
                        <div class="lp-number-v">∞</div>
                        <div class="lp-number-l">Tareas por área</div>
                    </div>
                    <div class="lp-number">
                        <div class="lp-number-v">100%</div>
                        <div class="lp-number-l">Control del progreso</div>
                    </div>
                    <div class="lp-number">
                        <div class="lp-number-v">0</div>
                        <div class="lp-number-l">Caos en el equipo</div>
                    </div>
                </div>
            </section>

            <!-- SLIDE 5: EMPEZAR -->
            <section class="lp-slide lp-slide-cta">
                <div class="lp-cta-card">
                    <img src="{{ asset('isotipo.png') }}" alt="Pixel" class="lp-cta-logo">
                    <p class="lp-eyebrow">Listo para empezar</p>
                    <h2 class="lp-cta-title">
                        Lleva tu equipo creativo<br>
                        <span class="grad-soft">al siguiente nivel</span>
                    </h2>
                    <p class="lp-cta-sub">Gratis. Sin tarjeta. Sin complicaciones.</p>
                    <div class="lp-cta-actions">
                        <a href="{{ route('registro') }}">
                            <button class="btn-hero btn-hero-primary">
                                <i class="fa-solid fa-rocket"></i> Crear cuenta gratis
                            </button>
                        </a>
                        <a href="{{ route('login') }}">
                            <button class="btn-hero btn-hero-secondary">Ya tengo cuenta</button>
                        </a>
                    </div>
                </div>
            </section>

        </div><!-- /.lp-track -->

        <!-- Flechas laterales -->
        <button class="lp-arrow lp-arrow-prev" id="lp-prev" aria-label="Anterior" disabled>
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class="lp-arrow lp-arrow-next" id="lp-next" aria-label="Siguiente">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

    </div><!-- /.lp-carousel -->

    <!-- FOOTER BAR con counter -->
    <div class="lp-footer-bar" style="justify-content:flex-end;">
        <div class="lp-counter">
            <span class="lp-counter-num" id="lp-counter-num">01</span>
            <span class="lp-counter-sep">/</span>
            <span class="lp-counter-tot">05</span>
        </div>
    </div>

    <script>
        const TOTAL      = 5;
        const track      = document.getElementById('lp-track');
        const navLinks   = document.querySelectorAll('.lp-nav-link');
        const counterNum = document.getElementById('lp-counter-num');
        const prevBtn    = document.getElementById('lp-prev');
        const nextBtn    = document.getElementById('lp-next');
        const themeBtn   = document.getElementById('lp-theme-btn');
        let slide = 0;

        function goTo(n) {
            slide = Math.max(0, Math.min(TOTAL - 1, n));
            track.style.transform = `translateX(-${slide * 100}%)`;
            navLinks.forEach((l, i) => l.classList.toggle('is-active', i === slide));
            counterNum.textContent = String(slide + 1).padStart(2, '0');
            prevBtn.disabled = slide === 0;
            nextBtn.disabled = slide === TOTAL - 1;
        }

        prevBtn.addEventListener('click', () => goTo(slide - 1));
        nextBtn.addEventListener('click', () => goTo(slide + 1));
        navLinks.forEach((l, i) => l.addEventListener('click', () => goTo(i)));

        themeBtn.addEventListener('click', () => {
            if (window.toggleTheme) window.toggleTheme();
        });

        goTo(0);
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
    body {
        flex-direction: column !important;
    }

    .auth {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    /* Anulamos la sombra del selector genérico `header` para este contexto */
    .auth-card {
        width: 100%;
        max-width: 420px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 36px 32px 32px;
        box-shadow: var(--shadow-card-hi);
        position: relative;
        overflow: hidden;
    }

    /* Borde de degradado sutil siempre visible */
    .auth-card::before {
        content: "";
        position: absolute;
        inset: 0;
        padding: 1px;
        border-radius: inherit;
        background: linear-gradient(135deg, rgba(20,184,166,.35), transparent 40%, transparent 60%, rgba(196,77,186,.35));
        mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        mask-composite: exclude;
        pointer-events: none;
    }

    .auth-card > * { position: relative; }

    /* Logo — sin herencia de estilos de <header> */
    .auth-logo-wrap {
        text-align: center;
        margin-bottom: 28px;
    }

    .auth-logo {
        font-size: 44px;
        line-height: 1;
        margin-bottom: 6px;
    }

    .auth-subtitle {
        color: var(--muted);
        font-size: 14px;
        margin-top: 6px;
    }

    .form-group {
        text-align: left;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted2);
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
    }

    .form-group input {
        width: 100%;
        border-radius: var(--radius-md);
    }

    .auth-actions {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .auth-actions .btn-primary {
        width: 100%;
        justify-content: center;
        padding: 13px;
        border-radius: var(--radius-md);
        font-size: 15px;
    }

    .auth-link {
        font-size: 13px;
        color: var(--muted);
        text-align: center;
    }

    .auth-link a {
        color: var(--magenta);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-link a:hover { text-decoration: underline; }

    /* Error flash */
    .auth-error {
        background: rgba(248,113,113,.10);
        border: 1px solid rgba(248,113,113,.30);
        border-radius: var(--radius-sm);
        color: #f87171;
        font-size: 13px;
        padding: 10px 14px;
        margin-bottom: 16px;
        text-align: center;
    }

    .auth-logo img {
        height: 60px;
        width: auto;
        vertical-align: middle;
    }
    </style>
</head>

<body class="magenta">

    <button class="theme-toggle-btn theme-toggle-fixed" id="theme-btn" title="Cambiar tema">
        <i class="fa-solid fa-sun" id="theme-icon"></i>
    </button>

    <main class="auth">
        <div class="auth-card">

            <!-- Logo — usa div, no header, para evitar estilos globales de header -->
            <div class="auth-logo-wrap">
                <div class="auth-logo pixel-logo">
                    <img src="{{ asset('isotipo.png') }}" alt="">PIXEL
                </div>
                <p class="auth-subtitle">Accede a tu cuenta</p>
            </div>

            @if($errors->any())
            <div class="auth-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <div class="auth-actions">
                    <button type="submit" class="btn-primary">Entrar</button>
                    <p class="auth-link">
                        ¿No tienes cuenta?
                        <a href="{{ route('registro') }}">Crear cuenta</a>
                    </p>
                </div>
            </form>

        </div>
    </main>

    <script>
        const themeBtn  = document.getElementById('theme-btn');
        const themeIcon = document.getElementById('theme-icon');

        function updateIcon() {
            const dark = document.documentElement.dataset.theme !== 'light';
            themeIcon.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }

        themeBtn.addEventListener('click', () => {
            if (window.toggleTheme) window.toggleTheme();
            setTimeout(updateIcon, 0);
        });

        new MutationObserver(updateIcon).observe(
            document.documentElement,
            { attributes: true, attributeFilter: ['data-theme'] }
        );

        updateIcon();
    </script>

</body>

</html>
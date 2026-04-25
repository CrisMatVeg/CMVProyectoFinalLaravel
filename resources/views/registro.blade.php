<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
        /* ======================================================
       LAYOUT
    ====================================================== */
        .auth {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-card {
            width: 100%;
            max-width: 460px;
        }

        /* ======================================================
       HEADER
    ====================================================== */
        .modal-header {
            width: 80%;
        }

        .auth-logo {
            font-family: "Robot";
            font-size: 50px;
            line-height: 1;
            margin-bottom: 10px;
        }

        .auth-subtitle {
            color: var(--muted);
            font-size: 15px;
        }

        /* ======================================================
       FORM
    ====================================================== */
        .form-group {
            text-align: left;
        }

        /* ======================================================
       ACTIONS & INTERACTIONS
    ====================================================== */
        .auth-actions {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Links */
        .auth-link {
            font-size: 13px;
            color: var(--muted);
            text-align: center;
        }

        a{
            color: var(--magenta);
        }
        a:hover{
            text-decoration:underline;
        }

        .invite-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 0, 128, 0.1);
            border: 1px solid var(--magenta);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .invite-banner i {
            color: var(--magenta);
            font-size: 1.2rem;
            flex-shrink: 0;
        }
    </style>
</head>

<body class="magenta">

    <main class="auth">
        <section class="main-card">

            <!-- HEADER -->
            <header class="modal-header">
                <h1 class="title-gradient auth-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</h1>
                <p class="auth-subtitle">
                    Crea tu cuenta
                </p>
            </header>

            @if(isset($invitacion) && $invitacion)
            <div class="invite-banner">
                <i class="fa-solid fa-envelope-open-text"></i>
                <p>Te invitaron al proyecto <strong>{{ $invitacion->proyecto->name }}</strong>. Crea tu cuenta para unirte.</p>
            </div>
            @endif

            <!-- FORM -->
            <form class="auth-form" method="post" action="{{ route('registro.post') }}">
                @csrf
                @if(isset($invitacion) && $invitacion)
                <input type="hidden" name="invite_code" value="{{ $invitacion->codigo }}">
                @endif
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="description">Nombre Completo</label>
                    <input type="description" id="description" name="description" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="auth-actions">
                    <button type="submit" class="btn-primary hover-lift">
                        Crear cuenta
                    </button></a>

                    <p class="auth-link">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}">Inicia sesión</a>
                    </p>
                </div>

            </form>

        </section>
    </main>

</body>

</html>
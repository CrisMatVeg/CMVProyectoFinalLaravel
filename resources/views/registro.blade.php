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

            <!-- FORM -->
            <form class="auth-form" method="post" action="#">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre de usuario</label>
                    <input type="text" id="name" name="name" required>
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
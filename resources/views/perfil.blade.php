<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Mi perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .auth {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .main-card {
            width: 100%;
            max-width: 480px;
        }

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

        .form-group {
            text-align: left;
        }

        .auth-actions {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .auth-link {
            font-size: 13px;
            color: var(--muted);
            text-align: center;
        }

        a {
            color: var(--magenta);
        }

        a:hover {
            text-decoration: underline;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0 20px;
        }

        .section-label {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(20, 184, 166, 0.12);
            border: 1px solid var(--teal);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            color: var(--teal);
        }
    </style>
</head>

<body class="magenta">

    <button class="theme-toggle-btn theme-toggle-fixed" onclick="window.toggleTheme()" title="Cambiar tema">
        <i class="fa-solid fa-circle-half-stroke"></i>
    </button>

    <main class="auth">
        <section class="main-card">

            <!-- HEADER -->
            <header class="modal-header">
                <h1 class="title-gradient auth-logo"><i class="fa-solid fa-dice-d6"></i>PIXEL</h1>
                <p class="auth-subtitle">Editar perfil</p>
            </header>

            @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf
                @method('PUT')

                <p class="section-label">Datos personales</p>

                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" id="username" name="username"
                        value="{{ old('username', $usuario->username) }}" required>
                    @error('username')
                        <span style="color:var(--magenta); font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Nombre completo</label>
                    <input type="text" id="description" name="description"
                        value="{{ old('description', $usuario->description) }}" required>
                    @error('description')
                        <span style="color:var(--magenta); font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $usuario->email) }}" required>
                    @error('email')
                        <span style="color:var(--magenta); font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <hr class="section-divider">
                <p class="section-label">Cambiar contraseña <span style="font-size:11px;">(opcional)</span></p>

                <div class="form-group">
                    <label for="password_actual">Contraseña actual</label>
                    <input type="password" id="password_actual" name="password_actual">
                    @error('password_actual')
                        <span style="color:var(--magenta); font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_nuevo">Nueva contraseña</label>
                    <input type="password" id="password_nuevo" name="password_nuevo">
                    @error('password_nuevo')
                        <span style="color:var(--magenta); font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-actions">
                    <button type="submit" class="btn-primary hover-lift">
                        Guardar cambios
                    </button>

                    <p class="auth-link">
                        <a href="{{ route('proyectos') }}">
                            <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i>
                            Volver a mis proyectos
                        </a>
                    </p>
                </div>

            </form>

        </section>
    </main>

</body>

</html>

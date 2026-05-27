<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Mi perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
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

        .auth-logo-wrap {
            margin-bottom: 28px;
        }

        .auth-logo {
            font-size: 30px;
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

        .auth-logo img {
            height: 60px;
            width: auto;
            vertical-align: middle;
        }

        .field-error {
            color: var(--magenta);
            font-size: 13px;
        }

        .label-optional {
            font-size: 11px;
        }

        .back-icon {
            font-size: 11px;
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
            <div class="auth-logo-wrap">
                <div class="auth-logo pixel-logo">
                    <img src="{{ asset('isotipo.png') }}" alt="">PIXEL
                </div>
                <p class="auth-subtitle">Editar perfil</p>
            </div>

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
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Nombre completo</label>
                    <input type="text" id="description" name="description"
                        value="{{ old('description', $usuario->description) }}" required>
                    @error('description')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $usuario->email) }}" required>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <hr class="section-divider">
                <p class="section-label">Cambiar contraseña <span class="label-optional">(opcional)</span></p>

                <div class="form-group">
                    <label for="password_actual">Contraseña actual</label>
                    <input type="password" id="password_actual" name="password_actual">
                    @error('password_actual')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_nuevo">Nueva contraseña</label>
                    <input type="password" id="password_nuevo" name="password_nuevo">
                    @error('password_nuevo')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-actions">
                    <button type="submit" class="btn-primary hover-lift">
                        Guardar cambios
                    </button>

                    <p class="auth-link">
                        <a href="{{ route('proyectos') }}">
                            <i class="fa-solid fa-arrow-left back-icon"></i>
                            Volver a mis proyectos
                        </a>
                    </p>
                </div>

            </form>

        </section>
    </main>

</body>

</html>

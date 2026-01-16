<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PIXEL | Bienvenido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')

    <style>
        /* ======================================================
       LAYOUT
    ====================================================== */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-content {
            width: 100%;
            max-width: 1200px;
            padding: 0 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* ======================================================
       HERO
    ====================================================== */
        .welcome-title {
            font-family: "Robot";
            font-size: 6vw;
            line-height: 1;
            animation: titleFadeIn 1.2s ease-out forwards;
            background-size: 5.5em;
        }

        .welcome-title i {
            margin-right: 25px;
        }

        .welcome-subtitle {
            font-size: 22px;
            margin-bottom: 18px;
            opacity: 0;
            animation: subtitleFadeIn 0.8s ease-out forwards;
            animation-delay: 0.9s;
        }

        .welcome-actions {
            margin-bottom: 60px;
            opacity: 0;
            animation: buttonFadeIn 0.8s ease-out forwards;
            animation-delay: 1.3s;
        }

        /* ======================================================
       FEATURES / CARDS
    ====================================================== */
        .welcome-features {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            opacity: 0;
            animation: featuresFadeIn 0.8s ease-out forwards;
            animation-delay: 1.7s;
        }

        .main-card {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            text-align: center;
            cursor: default;
        }

        .main-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: var(--radius-md);
        }

        .main-card p {
            font-size: var(--font-size-md);
            color: var(--muted);
        }

        /* ======================================================
       ANIMACIONES
    ====================================================== */
        @keyframes titleFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes subtitleFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes buttonFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes featuresFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <main>
        <section class="welcome-content">

            <!-- HERO -->
            <h1 class="title-gradient welcome-title"><i class="fa-solid fa-dice-d6"></i>PIXEL</h1>

            <p class="subtitle welcome-subtitle">
                ¡Bienvenido!
            </p>

            <div class="welcome-actions">
                <a href="{{ route('login') }}">
                    <button class="btn-primary hover-lift">
                        Entrar
                    </button>
                </a>
            </div>

            <!-- FEATURES -->
            <div class="welcome-features">

                <article class="main-card hover-lift teal">
                    <img src="img/feature1.jpg" alt="Descripción de la imagen 1">
                    <p>Organiza tus proyectos de forma visual y clara</p>
                </article>

                <article class="main-card hover-lift magenta">
                    <img src="img/feature2.jpg" alt="Descripción de la imagen 2">
                    <p>Colabora con tu equipo en un entorno creativo</p>
                </article>

                <article class="main-card hover-lift purple">
                    <img src="img/feature3.jpg" alt="Descripción de la imagen 3">
                    <p>Convierte ideas en experiencias jugables</p>
                </article>

            </div>

        </section>
    </main>

</body>

</html>
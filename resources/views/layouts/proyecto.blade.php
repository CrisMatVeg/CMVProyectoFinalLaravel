<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIXEL | @yield('titulo')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>(function(){var t=localStorage.getItem('pixel-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
  @vite('resources/css/app.css')
  @stack('head')
  @vite('resources/js/app.js')
  @stack('estilos')
</head>
<body class="@yield('body_class')">
  <x-sidebar :proyecto="$proyecto" :activo="$activo ?? ''" :tipoNombre="$tipoNombre ?? null" />
  <main>
    <div class="main-content">
      @yield('contenido')
    </div>
  </main>
  @stack('scripts')
  @stack('livewire')
</body>
</html>

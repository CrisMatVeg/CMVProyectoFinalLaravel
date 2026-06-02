@props([
    'proyecto',
    'activo'     => '',
    'tipoNombre' => null,
])

<aside class="sidebar">
    <div class="logo pixel-logo"><img src="{{ asset('isotipo.png') }}" alt="">PIXEL</div>

    <nav class="menu">
        <span class="menu-section">Proyecto</span>
        <a href="{{ route('proyecto', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'proyecto' ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
            <span>Proyecto</span>
        </a>

        @if($tipoNombre)
        <div class="menu-item hover-lift active">
            <span class="menu-icon"><i class="fa-solid fa-list-check"></i></span>
            <span>{{ $tipoNombre }}</span>
        </div>
        @endif

        <a href="{{ route('proyecto.gantt', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'gantt' ? 'active' : '' }}" data-mobile-hide="true">
            <span class="menu-icon"><i class="fa-solid fa-chart-gantt"></i></span>
            <span>Gantt</span>
        </a>
        <a href="{{ route('proyecto.calendario', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'calendario' ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span>
            <span>Calendario</span>
        </a>
        <a href="{{ route('proyecto.foro', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'foro' ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-comments"></i></span>
            <span>Foro</span>
        </a>

        @if(!$tipoNombre)
        <a href="{{ route('proyecto.archivos', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'archivos' ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-folder-open"></i></span>
            <span>Archivos</span>
        </a>
        @endif

        <a href="{{ route('proyecto.predefinicion', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'predefinicion' ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
            <span>Datos predefinidos</span>
        </a>

        @if($proyecto->created_by === auth()->id())
        <a href="{{ route('proyecto.miembros', $proyecto->id) }}" class="menu-item hover-lift {{ $activo === 'miembros' ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
            <span>Miembros</span>
        </a>
        @endif

        <span class="menu-section">General</span>
        <a href="{{ route('proyectos') }}" class="menu-item hover-lift">
            <span class="menu-icon"><i class="fa-solid fa-layer-group"></i></span>
            <span>Mis proyectos</span>
        </a>
    </nav>

    <div class="sidebar-user hover-lift">
        <a href="{{ route('perfil') }}" class="sidebar-user-link">
            <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
            <div class="user-info">
                <strong>{{ auth()->user()->username }}</strong>
                <span class="title-gradient">{{ $proyecto->created_by === auth()->id() ? 'Owner' : 'Miembro' }}</span>
            </div>
        </a>
        <button class="theme-toggle-btn" onclick="window.toggleTheme()" title="Cambiar tema">
            <i class="fa-solid fa-circle-half-stroke"></i>
        </button>
        <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
            @csrf
            <button type="submit" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
            </button>
        </form>
    </div>
</aside>

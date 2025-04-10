<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetroSport @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg p-2">
        <div class="container">
            <a class="navbar-brand" href="{{ route('lligues.index') }}">
                <img src="{{ asset('assets/iconos_nav/logo_metrosport.png') }}" alt="Metro Sport" height="70">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item ms-4 me-4">
                        <a class="nav-link {{ request()->routeIs('lligues.index') ? 'active' : '' }}" href="{{ route('lligues.index') }}">Lligues</a>
                    </li>
                    <li class="nav-item ms-4 me-4">
                        <a class="nav-link {{ request()->routeIs('crear-lliga') ? 'active' : '' }}" href="{{ route('crear-lliga') }}">Crea una lliga</a>
                    </li>
                    <li class="nav-item ms-4 me-4">
                        <a class="nav-link {{ request()->routeIs('preguntes') ? 'active' : '' }}" href="{{ route('preguntes') }}">Tens dubtes?</a>
                    </li>
                    <li class="nav-item ms-4 me-4">
                        @php
                            // Obtener el ID de la liga del equipo del usuario actual
                            $ligaId = DB::table('equip')
                                ->where('usuari_id_usuari', Auth::user()->id_usuari ?? null)
                                ->value('lliga_id_lliga');
                        @endphp

                        @if($ligaId)
                            <a class="nav-link {{ request()->routeIs('classificacio') ? 'active' : '' }}" href="{{ route('classificacio', ['id' => $ligaId]) }}">Classificació</a>
                        @else
                            <span class="nav-link text-muted" style="cursor: not-allowed;">Classificació (No inscrit)</span>
                        @endif
                    </li>
                </ul>
                <div class="ms-auto nav-icons d-flex align-items-center">
                    <a href="{{ route('notificacions.index') }}">
                        <img src="{{ asset('assets/iconos_nav/notificaciones.png') }}" alt="Notificaciones">
                    </a>
                    <a href="{{ route('editar-perfil') }}">
                        @php
                            $equipFoto = DB::table('equip')->where('usuari_id_usuari', Auth::user()->id_usuari)->value('url_imagen');
                        @endphp

                        @if($equipFoto && $equipFoto != '0')
                            <img src="{{ asset($equipFoto) }}" alt="Foto equipo" class="rounded-circle foto_nav" style="width: 35px; height: 35px; object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/iconos_nav/default.jpg') }}" alt="Perfil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                        @endif
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn-logout" type="submit"><img src="{{ asset('assets/iconos_nav/cerrar_sesion.png') }}" alt="Logout"></button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    @yield('chat')

</body>
</html>

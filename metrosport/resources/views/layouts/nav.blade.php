<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metro Sport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    <li class="nav-item ms-4 me-4"><a class="nav-link active" href="{{ route('lligues.index') }}">Lligues</a></li>
                    <li class="nav-item ms-4 me-4"><a class="nav-link" href="#">Crea una lliga</a></li>
                    <li class="nav-item ms-4 me-4"><a class="nav-link" href="#">Tens dubtes?</a></li>
                    <li class="nav-item ms-4 me-4"><a class="nav-link" href="#">Classificació</a></li>
                </ul>
                <div class="ms-auto nav-icons d-flex align-items-center">
                    <img src="{{ asset('assets/iconos_nav/notificaciones.png') }}" alt="Notificaciones">
                    <img src="{{ Auth::user()->foto_perfil ? asset('storage/' . Auth::user()->foto_perfil) : asset('assets/iconos_nav/default.png') }}" 
                        alt="Perfil">
                    <img src="{{ asset('assets/iconos_nav/cerrar_sesion.png') }}" alt="Logout">
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>

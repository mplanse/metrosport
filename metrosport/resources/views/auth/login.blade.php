<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex justify-content-center align-items-center vh-100 body-principal">
    <div class="position-absolute top-0 start-0 m-3">
        <img src="{{ asset('assets/iconos_nav/logo_blanc.png') }}" alt="Logo" style="max-width: 150px;">
    </div>

    <div class="card p-4 rounded-4 " style="width: 350px;">
        <h3 class="text-center">Iniciar Sessió</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="nom_usuari" class="form-label">Usuari</label>
                <input type="text" id="nom_usuari" name="nom_usuari" class="form-control rounded-4" required>
            </div>
            <div class="mb-3">
                <label for="contrasenya" class="form-label">Contrasenya</label>
                <input type="password" id="contrasenya" name="contrasenya" class="form-control rounded-4" required>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <button type="submit" class="btn p-2 w-50 btn-entrar rounded-4">Entrar</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <p class="mb-0">No tens conta? <a href="{{ route('register') }}">Registra't</a></p>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>

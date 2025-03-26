<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetroSport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container text-center mt-5">

        <div class="mb-4">
            <img src="{{ asset('assets/iconos_nav/logo_metrosport.png') }}" alt="Logo" class="img-fluid" style="max-width: 300px;">
        </div>

        <div class="mb-5">
            <p class="lead">On altres veuen carrers, nosaltres veiem escenaris de joc.</p>
            <p class="lead">L’esport urbà ens uneix.</p>
        </div>

        <div class="mb-4">
            <a href="{{ route('login') }}" class="btn btn-lg">Iniciar sesión</a>
        </div>
        <div>
            <a href="{{ route('register') }}" class="btn btn-lg">Registrarse</a>
        </div>
    </div>
</body>
</html>

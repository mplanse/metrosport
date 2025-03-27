<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 350px;">
        <h3 class="text-center">Registro</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('register1') }}">
            @csrf
            <div class="mb-3">
                <label for="nom_usuari" class="form-label">Usuario</label>
                <input type="text" id="nom_usuari" name="nom_usuari" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="mail" class="form-label">Correo Electrónico</label>
                <input type="email" id="mail" name="mail" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contrasenya" class="form-label">Contraseña</label>
                <input type="password" id="contrasenya" name="contrasenya" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contrasenya_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" id="contrasenya_confirmation" name="contrasenya_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Registrarse</button>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>

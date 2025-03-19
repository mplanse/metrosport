<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 350px;">
        <h3 class="text-center">Iniciar Sesión</h3>
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
                <label for="nom_usuari" class="form-label">Usuario</label>
                <input type="text" id="nom_usuari" name="nom_usuari" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contrasenya" class="form-label">Contraseña</label>
                <input type="password" id="contrasenya" name="contrasenya" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>
</body>
</html>

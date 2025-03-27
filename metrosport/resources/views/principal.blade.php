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
<body class="d-flex justify-content-center align-items-center body-principal">

    <div class="overlay"></div>
    <div class="text-center body-content">
        <div class="mb-4">
            <img src="{{ asset('assets/iconos_nav/logo_blanc.png') }}" alt="Logo" class="img-fluid" style="max-width: 300px;">
        </div>
        <div class="mb-5">
            <p class="lema">On altres veuen carrers, nosaltres veiem escenaris de joc.</p>
            <p class="lema">L’esport urbà ens uneix.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <a href="{{ route('login') }}" class="btn btn-lg">Iniciar sessió</a>
            </div>
            <div class="col-auto">
                <a href="{{ route('register') }}" class="btn btn-lg">Registrar-se</a>
            </div>
        </div>
    </div>
        <script>
            let currentBackgroundIndex = 0;
            const backgrounds = [
                '{{ asset('assets/fotos_fons/1.jpg') }}',
                '{{ asset('assets/fotos_fons/2.jpg') }}',
                '{{ asset('assets/fotos_fons/3.jpg') }}',
                '{{ asset('assets/fotos_fons/4.jpg') }}'
            ];

            function changeBackground() {
                currentBackgroundIndex = (currentBackgroundIndex + 1) % backgrounds.length;
                document.querySelector('.body-principal').style.backgroundImage = `url('${backgrounds[currentBackgroundIndex]}')`;
            }

            setInterval(changeBackground, 5000);

            changeBackground();
        </script>


</body>
</html>

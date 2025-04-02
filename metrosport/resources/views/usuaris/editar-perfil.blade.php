@extends('layouts.chat')
@section('title')
 · Perfil
@endsection
@section('content')
    <div class="container my-4">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if ($equip)
            <div class="d-flex justify-content-between align-items-start mb-4 container position-relative">
                <div class="d-flex align-items-center div_info">
                    @if($equip->url_imagen && $equip->url_imagen != '0')
                        <img src="{{ asset($equip->url_imagen) }}" alt="Escut de l'equip" class="rounded-circle foto_equip">
                    @else
                        <img src="{{ asset('assets/iconos_nav/default.jpg') }}" alt="Perfil" class="rounded-circle foto_equip">
                    @endif
                    <div class="ps-4 flex-grow-1">
                        @if ($equip->nom_equip !== '0')
                            <h1 class="mb-1">{{ $equip->nom_equip }}</h1>
                        @else
                            <h1 class="mb-1">ENCARA NO TENS NOM D'EQUIP</h1>
                        @endif
                        <p class="mb-0 text-muted mail-text">{{ auth()->user()->mail }}</p>
                    </div>
                </div>
                <a href="#" class="text-decoration-underline text-primary position-absolute top-0 end-0" id="editButton">Editar</a>
            </div>

                    {{-- Formulario para editar los datos --}}
                    <div id="editForm" style="display: none;">
                        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn" id="cancelEdit"
                                    style="color: #f44336;">Cancel·lar</button>
                                <button type="submit" class="btn btn-guardar">Guardar</button>
                            </div>
                            <div class="mb-3">
                                <label for="nom_equip" class="form-label">Nom de l'equip</label>
                                <input type="text" class="form-control" id="nom_equip" name="nom_equip"
                                    value="{{ $equip->nom_equip }}">
                            </div>
                            <div class="mb-3">
                                <label for="mail" class="form-label">Correu electrònic</label>
                                <input type="email" class="form-control" id="mail" name="mail"
                                    value="{{ auth()->user()->mail }}">
                            </div>
                            <div class="mb-3">
                                <label for="url_imagen" class="form-label">Foto de perfil de l'equip</label>
                                <input type="file" class="form-control" id="url_imagen" name="url_imagen">
                            </div>
                        </form>
                    </div>
                @endif

                {{-- SECCIÓN DE MAPAS --}}
                <div class="col-md-12">
                    @if ($equip && $equip->nom_ubicacio)
                        {{-- Vista para visualizar ubicación (visible cuando hay ubicación) --}}
                        <div id="view-map-container" style="display: block;">
                            <p class="text-muted">Ubicació actual: {{ $equip->nom_ubicacio }}</p>
                            <div id="view-map" class="my-4"></div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    mapboxgl.accessToken =
                                        'pk.eyJ1IjoibXBsYW5zZSIsImEiOiJjbThxMzZteHowaDhxMmlzY21xOXRiOWZhIn0.iCppyyNfsTUugnwH24A40Q';
                                    const mapboxClient = mapboxSdk({
                                        accessToken: mapboxgl.accessToken
                                    });

                                    mapboxClient.geocoding
                                        .forwardGeocode({
                                            query: @json($equip->nom_ubicacio),
                                            autocomplete: false,
                                            limit: 1
                                        })
                                        .send()
                                        .then((response) => {
                                            if (!response || !response.body || !response.body.features || !response.body.features
                                                .length) {
                                                console.error('Ubicación no encontrada');
                                                return;
                                            }

                                            const feature = response.body.features[0];

                                            const viewMap = new mapboxgl.Map({
                                                container: 'view-map',
                                                style: 'mapbox://styles/mapbox/streets-v12',
                                                center: feature.center,
                                                zoom: 13
                                            });

                                            new mapboxgl.Marker().setLngLat(feature.center).addTo(viewMap);
                                        });
                                });
                            </script>
                        </div>
                        <div id="edit-map-container" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Mode edició:</strong> Configura la ubicació del teu equip utilitzant el cercador.
                            </div>

                            <form action="{{ route('equip.setUbicacio') }}" method="POST">
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="submit" class="btn mt-2 btn-guardar">Guardar ubicació actalitzada</button>
                                </div>
                                @csrf
                                <input type="hidden" name="nom_ubicacio" id="nom_ubicacio" value="{{ $equip->nom_ubicacio ?? '' }}">
                                <div id="edit-map" class="my-3"></div>
                            </form>
                        </div>
                    @else
                        {{-- Si NO hay ubicación configurada: mostrar directamente el panel de edición --}}
                        <div id="view-map-container" style="display: none;">
                            <div class="alert alert-info">
                                No hi ha cap ubicació configurada per a aquest equip.
                            </div>
                        </div>
                        <div id="edit-map-container" style="display: block;">
                            <div class="alert alert-info">
                                <strong>Configura la ubicació:</strong> El teu equip encara no té una ubicació configurada. Si us plau, configura la ubicació utilitzant el cercador.
                            </div>

                            <form action="{{ route('equip.setUbicacio') }}" method="POST">
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="submit" class="btn mt-2 btn-guardar">Guardar ubicació</button>
                                </div>
                                @csrf
                                <input type="hidden" name="nom_ubicacio" id="nom_ubicacio" value="">
                                <div id="edit-map" class="my-3"></div>
                            </form>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Inicializar inmediatamente el mapa de edición ya que no hay ubicación
                                    if (window.initEditMap) {
                                        window.initEditMap();
                                    } else {
                                        // Fallback por si la función no está disponible todavía
                                        mapboxgl.accessToken = 'pk.eyJ1IjoibXBsYW5zZSIsImEiOiJjbThxMzNlaWowZnpmMmpzYTdpd2huODN1In0.DF7JMs8WTd89SynWu1bkiw';

                                        const editMap = new mapboxgl.Map({
                                            container: 'edit-map',
                                            style: 'mapbox://styles/mapbox/streets-v12',
                                            center: [2.1734, 41.3851], // Barcelona por defecto
                                            zoom: 12
                                        });

                                        const geocoder = new MapboxGeocoder({
                                            accessToken: mapboxgl.accessToken,
                                            mapboxgl: mapboxgl,
                                            placeholder: 'Busca una dirección...'
                                        });

                                        editMap.addControl(geocoder);

                                        geocoder.on('result', function(e) {
                                            document.getElementById('nom_ubicacio').value = e.result.place_name;
                                        });

                                        window.editMapInitialized = true;
                                    }
                                });
                            </script>
                        </div>
                    @endif
                </div>

                {{-- Carga de bibliotecas de Mapbox --}}
                <link href="https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.css" rel="stylesheet" />
                <script src="https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.js"></script>
                <script src="https://unpkg.com/@mapbox/mapbox-sdk/umd/mapbox-sdk.min.js"></script>
                <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.3/mapbox-gl-geocoder.min.js"></script>
                <link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.3/mapbox-gl-geocoder.css"
                    rel="stylesheet" />

                {{-- Script para alternar entre modos de visualización y edición --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('editButton').addEventListener('click', function(e) {
                            e.preventDefault();
                            // Mostrar formulario de edición
                            document.getElementById('editForm').style.display = 'block';
                            this.style.display = 'none';

                            // Cambiar la vista del mapa
                            document.getElementById('view-map-container').style.display = 'none';
                            document.getElementById('edit-map-container').style.display = 'block';

                            // Inicializar el mapa de edición cuando se muestra
                            if (window.initEditMap) {
                                window.initEditMap();
                            }
                        });

                        document.getElementById('cancelEdit').addEventListener('click', function() {
                            // Ocultar formulario de edición
                            document.getElementById('editForm').style.display = 'none';
                            document.getElementById('editButton').style.display = 'inline';

                            // Restaurar la vista del mapa
                            document.getElementById('view-map-container').style.display = 'block';
                            document.getElementById('edit-map-container').style.display = 'none';
                        });
                    });
                </script>
            </div>
        </div>

        {{-- Formulario de horarios --}}
        <div class="col-md-12 pt-5">
            <form action="{{ route('dia_hora.store') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-guardar">Guardar horari actualitzat</button>
                    </div>

                    <table class="table table-bordered" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Hora</th>
                                <th>Lun</th>
                                <th>Mar</th>
                                <th>Mié</th>
                                <th>Jue</th>
                                <th>Vie</th>
                                <th>Sáb</th>
                                <th>Dom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>00:00 - 01:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckLun00"
                                            name="checkbox_1" {{ in_array(1, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun00"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="25"
                                            id="flexCheckMar00" name="checkbox_25"
                                            {{ in_array(25, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar00"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="49"
                                            id="flexCheckMie00" name="checkbox_49"
                                            {{ in_array(49, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie00"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="73"
                                            id="flexCheckJue00" name="checkbox_73"
                                            {{ in_array(73, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue00"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="97"
                                            id="flexCheckVie00" name="checkbox_97"
                                            {{ in_array(97, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie00"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="121"
                                            id="flexCheckSab00" name="checkbox_121"
                                            {{ in_array(121, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab00"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="145"
                                            id="flexCheckDom00" name="checkbox_145"
                                            {{ in_array(145, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom00"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>01:00 - 02:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="2"
                                            id="flexCheckLun01" name="checkbox_2"
                                            {{ in_array(2, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun01"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="26"
                                            id="flexCheckMar01" name="checkbox_26"
                                            {{ in_array(26, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar01"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="50"
                                            id="flexCheckMie01" name="checkbox_50"
                                            {{ in_array(50, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie01"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="74"
                                            id="flexCheckJue01" name="checkbox_74"
                                            {{ in_array(74, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue01"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="98"
                                            id="flexCheckVie01" name="checkbox_98"
                                            {{ in_array(98, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie01"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="122"
                                            id="flexCheckSab01" name="checkbox_122"
                                            {{ in_array(122, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab01"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="146"
                                            id="flexCheckDom01" name="checkbox_146"
                                            {{ in_array(146, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom01"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>02:00 - 03:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="3"
                                            id="flexCheckLun02" name="checkbox_3"
                                            {{ in_array(3, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun02"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="27"
                                            id="flexCheckMar02" name="checkbox_27"
                                            {{ in_array(27, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar02"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="51"
                                            id="flexCheckMie02" name="checkbox_51"
                                            {{ in_array(51, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie02"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="75"
                                            id="flexCheckJue02" name="checkbox_75"
                                            {{ in_array(75, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue02"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="99"
                                            id="flexCheckVie02" name="checkbox_99"
                                            {{ in_array(99, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie02"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="123"
                                            id="flexCheckSab02" name="checkbox_123"
                                            {{ in_array(123, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab02"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="147"
                                            id="flexCheckDom02" name="checkbox_147"
                                            {{ in_array(147, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom02"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>03:00 - 04:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="4"
                                            id="flexCheckLun03" name="checkbox_4"
                                            {{ in_array(4, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun03"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="28"
                                            id="flexCheckMar03" name="checkbox_28"
                                            {{ in_array(28, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar03"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="52"
                                            id="flexCheckMie03" name="checkbox_52"
                                            {{ in_array(52, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie03"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="76"
                                            id="flexCheckJue03" name="checkbox_76"
                                            {{ in_array(76, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue03"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="100"
                                            id="flexCheckVie03" name="checkbox_100"
                                            {{ in_array(100, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie03"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="124"
                                            id="flexCheckSab03" name="checkbox_124"
                                            {{ in_array(124, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab03"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="148"
                                            id="flexCheckDom03" name="checkbox_148"
                                            {{ in_array(148, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom03"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>04:00 - 05:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="5"
                                            id="flexCheckLun04" name="checkbox_5"
                                            {{ in_array(5, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun04"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="29"
                                            id="flexCheckMar04" name="checkbox_29"
                                            {{ in_array(29, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar04"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="53"
                                            id="flexCheckMie04" name="checkbox_53"
                                            {{ in_array(53, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie04"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="77"
                                            id="flexCheckJue04" name="checkbox_77"
                                            {{ in_array(77, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue04"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="101"
                                            id="flexCheckVie04" name="checkbox_101"
                                            {{ in_array(101, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie04"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="125"
                                            id="flexCheckSab04" name="checkbox_125"
                                            {{ in_array(125, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab04"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="149"
                                            id="flexCheckDom04" name="checkbox_149"
                                            {{ in_array(149, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom04"></label>
                                    </div>
                                </td>
                            </tr>
                            <td>05:00 - 06:00</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="6" id="flexCheckLun05"
                                        name="checkbox_6" {{ in_array(6, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckLun05"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="30" id="flexCheckMar05"
                                        name="checkbox_30" {{ in_array(30, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckMar05"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="54" id="flexCheckMie05"
                                        name="checkbox_54" {{ in_array(54, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckMie05"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="78" id="flexCheckJue05"
                                        name="checkbox_78" {{ in_array(78, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckJue05"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="102" id="flexCheckVie05"
                                        name="checkbox_102" {{ in_array(102, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckVie05"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="126" id="flexCheckSab05"
                                        name="checkbox_126" {{ in_array(126, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckSab05"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="150" id="flexCheckDom05"
                                        name="checkbox_150" {{ in_array(150, $horasMarcadas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckDom05"></label>
                                </div>
                            </td>
                            </tr>
                            <tr>
                                <td>06:00 - 07:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="7"
                                            id="flexCheckLun06" name="checkbox_7"
                                            {{ in_array(7, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun06"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="31"
                                            id="flexCheckMar06" name="checkbox_31"
                                            {{ in_array(31, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar06"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="55"
                                            id="flexCheckMie06" name="checkbox_55"
                                            {{ in_array(55, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie06"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="79"
                                            id="flexCheckJue06" name="checkbox_79"
                                            {{ in_array(79, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue06"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="103"
                                            id="flexCheckVie06" name="checkbox_103"
                                            {{ in_array(103, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie06"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="127"
                                            id="flexCheckSab06" name="checkbox_127"
                                            {{ in_array(127, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab06"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="151"
                                            id="flexCheckDom06" name="checkbox_151"
                                            {{ in_array(151, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom06"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>07:00 - 08:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="8"
                                            id="flexCheckLun07" name="checkbox_8"
                                            {{ in_array(8, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun07"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="32"
                                            id="flexCheckMar07" name="checkbox_32"
                                            {{ in_array(32, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar07"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="56"
                                            id="flexCheckMie07" name="checkbox_56"
                                            {{ in_array(56, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie07"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="80"
                                            id="flexCheckJue07" name="checkbox_80"
                                            {{ in_array(80, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue07"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="104"
                                            id="flexCheckVie07" name="checkbox_104"
                                            {{ in_array(104, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie07"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="128"
                                            id="flexCheckSab07" name="checkbox_128"
                                            {{ in_array(128, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab07"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="152"
                                            id="flexCheckDom07" name="checkbox_152"
                                            {{ in_array(152, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom07"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>08:00 - 09:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="9"
                                            id="flexCheckLun08" name="checkbox_9"
                                            {{ in_array(9, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun08"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="33"
                                            id="flexCheckMar08" name="checkbox_33"
                                            {{ in_array(33, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar08"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="57"
                                            id="flexCheckMie08" name="checkbox_57"
                                            {{ in_array(57, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie08"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="81"
                                            id="flexCheckJue08" name="checkbox_81"
                                            {{ in_array(81, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue08"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="105"
                                            id="flexCheckVie08" name="checkbox_105"
                                            {{ in_array(105, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie08"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="129"
                                            id="flexCheckSab08" name="checkbox_129"
                                            {{ in_array(129, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab08"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="153"
                                            id="flexCheckDom08" name="checkbox_153"
                                            {{ in_array(153, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom08"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>09:00 - 10:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="10"
                                            id="flexCheckLun09" name="checkbox_10"
                                            {{ in_array(10, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun09"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="34"
                                            id="flexCheckMar09" name="checkbox_34"
                                            {{ in_array(34, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar09"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="58"
                                            id="flexCheckMie09" name="checkbox_58"
                                            {{ in_array(58, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie09"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="82"
                                            id="flexCheckJue09" name="checkbox_82"
                                            {{ in_array(82, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue09"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="106"
                                            id="flexCheckVie09" name="checkbox_106"
                                            {{ in_array(106, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie09"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="130"
                                            id="flexCheckSab09" name="checkbox_130"
                                            {{ in_array(130, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab09"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="154"
                                            id="flexCheckDom09" name="checkbox_154"
                                            {{ in_array(154, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom09"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>10:00 - 11:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="11"
                                            id="flexCheckLun10" name="checkbox_11"
                                            {{ in_array(11, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun10"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="35"
                                            id="flexCheckMar10" name="checkbox_35"
                                            {{ in_array(35, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar10"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="59"
                                            id="flexCheckMie10" name="checkbox_59"
                                            {{ in_array(59, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie10"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="83"
                                            id="flexCheckJue10" name="checkbox_83"
                                            {{ in_array(83, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue10"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="107"
                                            id="flexCheckVie10" name="checkbox_107"
                                            {{ in_array(107, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie10"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="131"
                                            id="flexCheckSab10" name="checkbox_131"
                                            {{ in_array(131, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab10"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="155"
                                            id="flexCheckDom10" name="checkbox_155"
                                            {{ in_array(155, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom10"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>11:00 - 12:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="12"
                                            id="flexCheckLun11" name="checkbox_12"
                                            {{ in_array(12, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun11"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="36"
                                            id="flexCheckMar11" name="checkbox_36"
                                            {{ in_array(36, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar11"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="60"
                                            id="flexCheckMie11" name="checkbox_60"
                                            {{ in_array(60, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie11"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="84"
                                            id="flexCheckJue11" name="checkbox_84"
                                            {{ in_array(84, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue11"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="108"
                                            id="flexCheckVie11" name="checkbox_108"
                                            {{ in_array(108, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie11"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="132"
                                            id="flexCheckSab11" name="checkbox_132"
                                            {{ in_array(132, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab11"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="156"
                                            id="flexCheckDom11" name="checkbox_156"
                                            {{ in_array(156, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom11"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>12:00 - 13:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="13"
                                            id="flexCheckLun12" name="checkbox_13"
                                            {{ in_array(13, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun12"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="37"
                                            id="flexCheckMar12" name="checkbox_37"
                                            {{ in_array(37, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar12"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="61"
                                            id="flexCheckMie12" name="checkbox_61"
                                            {{ in_array(61, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie12"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="85"
                                            id="flexCheckJue12" name="checkbox_85"
                                            {{ in_array(85, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue12"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="109"
                                            id="flexCheckVie12" name="checkbox_109"
                                            {{ in_array(109, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie12"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="133"
                                            id="flexCheckSab12" name="checkbox_133"
                                            {{ in_array(133, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab12"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="157"
                                            id="flexCheckDom12" name="checkbox_157"
                                            {{ in_array(157, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom12"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>13:00 - 14:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="14"
                                            id="flexCheckLun13" name="checkbox_14"
                                            {{ in_array(14, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun13"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="38"
                                            id="flexCheckMar13" name="checkbox_38"
                                            {{ in_array(38, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar13"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="62"
                                            id="flexCheckMie13" name="checkbox_62"
                                            {{ in_array(62, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie13"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="86"
                                            id="flexCheckJue13" name="checkbox_86"
                                            {{ in_array(86, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue13"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="110"
                                            id="flexCheckVie13" name="checkbox_110"
                                            {{ in_array(110, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie13"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="134"
                                            id="flexCheckSab13" name="checkbox_134"
                                            {{ in_array(134, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab13"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="158"
                                            id="flexCheckDom13" name="checkbox_158"
                                            {{ in_array(158, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom13"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>14:00 - 15:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="15"
                                            id="flexCheckLun14" name="checkbox_15"
                                            {{ in_array(15, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun14"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="39"
                                            id="flexCheckMar14" name="checkbox_39"
                                            {{ in_array(39, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar14"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="63"
                                            id="flexCheckMie14" name="checkbox_63"
                                            {{ in_array(63, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie14"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="87"
                                            id="flexCheckJue14" name="checkbox_87"
                                            {{ in_array(87, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue14"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="111"
                                            id="flexCheckVie14" name="checkbox_111"
                                            {{ in_array(111, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie14"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="135"
                                            id="flexCheckSab14" name="checkbox_135"
                                            {{ in_array(135, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab14"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="159"
                                            id="flexCheckDom14" name="checkbox_159"
                                            {{ in_array(159, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom14"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>15:00 - 16:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="16"
                                            id="flexCheckLun15" name="checkbox_16"
                                            {{ in_array(16, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun15"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="40"
                                            id="flexCheckMar15" name="checkbox_40"
                                            {{ in_array(40, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar15"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="64"
                                            id="flexCheckMie15" name="checkbox_64"
                                            {{ in_array(64, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie15"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="88"
                                            id="flexCheckJue15" name="checkbox_88"
                                            {{ in_array(88, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue15"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="112"
                                            id="flexCheckVie15" name="checkbox_112"
                                            {{ in_array(112, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie15"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="136"
                                            id="flexCheckSab15" name="checkbox_136"
                                            {{ in_array(136, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab15"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="160"
                                            id="flexCheckDom15" name="checkbox_160"
                                            {{ in_array(160, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom15"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>16:00 - 17:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="17"
                                            id="flexCheckLun16" name="checkbox_17"
                                            {{ in_array(17, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun16"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="41"
                                            id="flexCheckMar16" name="checkbox_41"
                                            {{ in_array(41, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar16"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="65"
                                            id="flexCheckMie16" name="checkbox_65"
                                            {{ in_array(65, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie16"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="89"
                                            id="flexCheckJue16" name="checkbox_89"
                                            {{ in_array(89, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue16"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="113"
                                            id="flexCheckVie16" name="checkbox_113"
                                            {{ in_array(113, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie16"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="137"
                                            id="flexCheckSab16" name="checkbox_137"
                                            {{ in_array(137, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab16"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="161"
                                            id="flexCheckDom16" name="checkbox_161"
                                            {{ in_array(161, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom16"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>17:00 - 18:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="18"
                                            id="flexCheckLun17" name="checkbox_18"
                                            {{ in_array(18, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun17"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="42"
                                            id="flexCheckMar17" name="checkbox_42"
                                            {{ in_array(42, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar17"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="66"
                                            id="flexCheckMie17" name="checkbox_66"
                                            {{ in_array(66, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie17"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="90"
                                            id="flexCheckJue17" name="checkbox_90"
                                            {{ in_array(90, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue17"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="114"
                                            id="flexCheckVie17" name="checkbox_114"
                                            {{ in_array(114, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie17"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="138"
                                            id="flexCheckSab17" name="checkbox_138"
                                            {{ in_array(138, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab17"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="162"
                                            id="flexCheckDom17" name="checkbox_162"
                                            {{ in_array(162, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom17"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>18:00 - 19:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="19"
                                            id="flexCheckLun18" name="checkbox_19"
                                            {{ in_array(19, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun18"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="43"
                                            id="flexCheckMar18" name="checkbox_43"
                                            {{ in_array(43, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar18"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="67"
                                            id="flexCheckMie18" name="checkbox_67"
                                            {{ in_array(67, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie18"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="91"
                                            id="flexCheckJue18" name="checkbox_91"
                                            {{ in_array(91, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue18"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="115"
                                            id="flexCheckVie18" name="checkbox_115"
                                            {{ in_array(115, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie18"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="139"
                                            id="flexCheckSab18" name="checkbox_139"
                                            {{ in_array(139, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab18"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="163"
                                            id="flexCheckDom18" name="checkbox_163"
                                            {{ in_array(163, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom18"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>19:00 - 20:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="20"
                                            id="flexCheckLun19" name="checkbox_20"
                                            {{ in_array(20, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun19"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="44"
                                            id="flexCheckMar19" name="checkbox_44"
                                            {{ in_array(44, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar19"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="68"
                                            id="flexCheckMie19" name="checkbox_68"
                                            {{ in_array(68, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie19"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="92"
                                            id="flexCheckJue19" name="checkbox_92"
                                            {{ in_array(92, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue19"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="116"
                                            id="flexCheckVie19" name="checkbox_116"
                                            {{ in_array(116, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie19"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="140"
                                            id="flexCheckSab19" name="checkbox_140"
                                            {{ in_array(140, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab19"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="164"
                                            id="flexCheckDom19" name="checkbox_164"
                                            {{ in_array(164, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom19"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>20:00 - 21:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="21"
                                            id="flexCheckLun20" name="checkbox_21"
                                            {{ in_array(21, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun20"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="45"
                                            id="flexCheckMar20" name="checkbox_45"
                                            {{ in_array(45, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar20"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="69"
                                            id="flexCheckMie20" name="checkbox_69"
                                            {{ in_array(69, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie20"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="93"
                                            id="flexCheckJue20" name="checkbox_93"
                                            {{ in_array(93, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue20"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="117"
                                            id="flexCheckVie20" name="checkbox_117"
                                            {{ in_array(117, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie20"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="141"
                                            id="flexCheckSab20" name="checkbox_141"
                                            {{ in_array(141, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab20"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="165"
                                            id="flexCheckDom20" name="checkbox_165"
                                            {{ in_array(165, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom20"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>21:00 - 22:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="22"
                                            id="flexCheckLun21" name="checkbox_22"
                                            {{ in_array(22, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun21"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="46"
                                            id="flexCheckMar21" name="checkbox_46"
                                            {{ in_array(46, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar21"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="70"
                                            id="flexCheckMie21" name="checkbox_70"
                                            {{ in_array(70, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie21"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="94"
                                            id="flexCheckJue21" name="checkbox_94"
                                            {{ in_array(94, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue21"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="118"
                                            id="flexCheckVie21" name="checkbox_118"
                                            {{ in_array(118, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie21"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="142"
                                            id="flexCheckSab21" name="checkbox_142"
                                            {{ in_array(142, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab21"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="166"
                                            id="flexCheckDom21" name="checkbox_166"
                                            {{ in_array(166, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom21"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>22:00 - 23:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="23"
                                            id="flexCheckLun22" name="checkbox_23"
                                            {{ in_array(23, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun22"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="47"
                                            id="flexCheckMar22" name="checkbox_47"
                                            {{ in_array(47, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar22"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="71"
                                            id="flexCheckMie22" name="checkbox_71"
                                            {{ in_array(71, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie22"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="95"
                                            id="flexCheckJue22" name="checkbox_95"
                                            {{ in_array(95, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue22"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="119"
                                            id="flexCheckVie22" name="checkbox_119"
                                            {{ in_array(119, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie22"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="143"
                                            id="flexCheckSab22" name="checkbox_143"
                                            {{ in_array(143, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab22"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="167"
                                            id="flexCheckDom22" name="checkbox_167"
                                            {{ in_array(167, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom22"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>23:00 - 24:00</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="24"
                                            id="flexCheckLun23" name="checkbox_24"
                                            {{ in_array(24, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckLun23"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="48"
                                            id="flexCheckMar23" name="checkbox_48"
                                            {{ in_array(48, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMar23"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="72"
                                            id="flexCheckMie23" name="checkbox_72"
                                            {{ in_array(72, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckMie23"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="96"
                                            id="flexCheckJue23" name="checkbox_96"
                                            {{ in_array(96, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckJue23"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="120"
                                            id="flexCheckVie23" name="checkbox_120"
                                            {{ in_array(120, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckVie23"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="144"
                                            id="flexCheckSab23" name="checkbox_144"
                                            {{ in_array(144, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckSab23"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="168"
                                            id="flexCheckDom23" name="checkbox_168"
                                            {{ in_array(168, $horasMarcadas) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDom23"></label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection

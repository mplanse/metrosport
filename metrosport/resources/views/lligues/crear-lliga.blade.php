@extends('layouts.nav')

@section('content')
<div class="container">
    <h1>Crear Lliga</h1>

    {{-- Mostra errors si l'usuari no té configurada la ubicació --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('lligues.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nom_lliga" class="form-label">Nom de la Lliga</label>
            <input type="text" class="form-control" id="nom_lliga" name="nom_lliga" required>
        </div>

        <div class="mb-3">
            <label for="url_imagen" class="form-label">Imatge</label>
            <input type="file" class="form-control" id="url_imagen" name="url_imagen" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="esport" class="form-label">Esport</label>
            <input type="text" class="form-control" id="esport" name="esport" required>
        </div>

        <!-- Campo de ubicació eliminado, se obtiene automàticament en el controlador -->

        <div class="mb-3">
            <label for="nro_equips_participants" class="form-label">Número d'Equips Participants</label>
            <input type="number" class="form-control" id="nro_equips_participants" name="nro_equips_participants" min="2" required>
            <small class="text-muted">Mínim 2 equips.</small>
        </div>

        {{-- <div class="mb-3">
            <label for="data_inici" class="form-label">Data d'Inici</label>
            <input type="date" class="form-control" id="data_inici" name="data_inici" required>
            <small class="text-muted">La data de fi es calcularà automàticament (2 setmanes per cada equip participant, ex: 10 equips = 20 setmanes després de la data d'inici).</small>
        </div> --}}

        <div class="mb-3">
            <label for="persones_equip" class="form-label">Nombre de persones per equip</label>
            <input type="number" class="form-control" id="persones_equip" name="persones_equip" min="1" required>
        </div>

        <div class="mb-3">
            <label for="preu_entrada" class="form-label">Preu d'Entrada</label>
            <input type="number" class="form-control" id="preu_entrada" name="preu_entrada" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear Lliga</button>
    </form>
</div>
@endsection

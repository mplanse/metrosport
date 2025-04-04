@extends('layouts.chat')

@section('content')
<div id="app" class="container mt-4">
    <!-- Pasamos la información de compatibilidad y usuario inscrito al componente Vue -->
    @php
        $controller = new \App\Http\Controllers\LligaController();
        $compatibilidad = null;

        if (auth()->check()) {
            $compatibilidad = $controller->verificarCompatibilidadUnirse($id)->getData(true);
        }
    @endphp

    <lliga :id="{{ $id }}"
           :compatibilidad-prop="{{ json_encode($compatibilidad ?? ['compatible' => false]) }}">
    </lliga>
</div>
@endsection


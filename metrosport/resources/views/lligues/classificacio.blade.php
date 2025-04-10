@extends('layouts.nav')

@section('title', 'Classificació')

@section('content')
<div class="container mt-4">
    <h1>Classificació de la Lliga: {{ $liga->nom_lliga }}</h1>

    @if($partidos->isEmpty())
        <p class="text-center mt-4">Encara no hi ha partits programats per aquesta lliga.</p>
    @else
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Jornada</th>
                    <th>Equip Local</th>
                    <th>Equip Visitant</th>
                    <th>Dia</th>
                    <th>Hora</th>
                    <th>Ubicació</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partidos as $partido)
                    <tr>
                        <td>{{ $partido->jornada }}</td>
                        <td>
                            {{ $partido->equips->where('pivot.local_visitant', 'local')->first()->nom_equip ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $partido->equips->where('pivot.local_visitant', 'visitant')->first()->nom_equip ?? 'N/A' }}
                        </td>
                        <td>{{ $partido->diaHora->dia ?? 'N/A' }}</td>
                        <td>{{ $partido->diaHora->hora ?? 'N/A' }}:00</td>
                        <td>{{ $partido->ubicacio->nom_ubicacio ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

@extends('layouts.nav')

@section('title', 'Classificació')

@section('content')
<div class="container mt-4">
    <h1>Jornades de la Lliga: {{ $liga->nom_lliga }}</h1>

    @if($partidosAgrupados->isEmpty())
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
                    <th>Data</th> {{-- Nueva columna --}}
                    <th>Ubicació</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Array para convertir números en días de la semana
                    $diasSemana = [
                        1 => 'Dilluns', // Lunes
                        2 => 'Dimarts', // Martes
                        3 => 'Dimecres', // Miércoles
                        4 => 'Dijous', // Jueves
                        5 => 'Divendres', // Viernes
                        6 => 'Dissabte', // Sábado
                        7 => 'Diumenge'  // Domingo
                    ];
                @endphp
                @foreach ($partidosAgrupados as $jornada => $partidos)
                    @foreach ($partidos as $partido)
                        @php
                            // Obtener los equipos local y visitante
                            $equipLocal = $partido->equips->where('pivot.local_visitant', 'local')->first();
                            $equipVisitant = $partido->equips->where('pivot.local_visitant', 'visitant')->first();
                        @endphp
                        @if ($equipLocal && $equipVisitant) {{-- Mostrar solo si ambos equipos existen --}}
                        <tr>
                            <td>{{ $jornada }}</td>
                            <td>{{ $equipLocal->nom_equip ?? 'N/A' }}</td>
                            <td>{{ $equipVisitant->nom_equip ?? 'N/A' }}</td>
                            <td>{{ $diasSemana[$partido->diaHora->dia] ?? 'N/A' }}</td>
                            <td>{{ $partido->diaHora->hora ?? 'N/A' }}:00</td>
                            <td>{{ $partido->data ?? 'N/A' }}</td> {{-- Mostrar la fecha --}}
                            <td>{{ $partido->ubicacio->nom_ubicacio ?? 'N/A' }}</td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

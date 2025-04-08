@extends('layouts.chat')

@section('title')
    · Notificacions
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Notificacions</h1>

        @if ($notificacions->isEmpty())
            <div class="alert alert-warning text-center" role="alert">
                No hi ha notificacions.
            </div>
        @else
            <div class="list-group">
                @foreach ($notificacions as $notificacio)
                    <div class="notification-card">
                        <div class="notification-content">
                            <p class="mb-1">{{ $notificacio->missatge_notificacio }}</p>
                        </div>
                        <div class="notification-footer">
                            <small>
                                <strong>Data:</strong> {{ \Carbon\Carbon::parse($notificacio->timestamp)->format('d/m/Y') }}
                                <br>
                                <strong>Hora:</strong> {{ \Carbon\Carbon::parse($notificacio->timestamp)->format('H:i') }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@extends('layouts.chat')

@section('title')
 · Notificacions
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Notificacions</h1>

    @if($notificacions->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No hi ha notificacions.
        </div>
    @else
        <div class="list-group">
            @foreach($notificacions as $notificacio)
                <div class="list-group-item d-flex justify-content-between align-items-start border-0 rounded-3 shadow-sm mb-3">
                    <div class="notification-content">
                        <p class="mb-1 text-wrap">{{ $notificacio->missatge_notificacio }}</p>
                    </div>
                    <div class="notification-footer text-end">
                        <small class="text-muted">
                            <strong>Data:</strong> {{ \Carbon\Carbon::parse($notificacio->timestamp)->format('d/m/Y') }} <br>
                            <strong>Hora:</strong> {{ \Carbon\Carbon::parse($notificacio->timestamp)->format('H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

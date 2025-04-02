@extends('layouts.chat')

@section('title')
 · Notificacions
@endsection

@section('content')
<div class="container">
    <h1>Notificacions</h1>

    @if($notificacions->isEmpty())
        <p>No hi ha notificacions.</p>
    @else
        <ul class="list-group">
            @foreach($notificacions as $notificacio)
                <li class="list-group-item">
                    {{ $notificacio->missatge_notificacio }}
                </li>
                <li class="list-group-item">
                    <strong>Data:</strong> {{ $notificacio->timestamp }}
            @endforeach
        </ul>
    @endif
</div>
@endsection

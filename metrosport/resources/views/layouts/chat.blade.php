@extends('layouts.nav')

@section('chat')
<div class="chat">
    <a href="{{ route('chat.index') }}"><img src="{{ asset('assets/iconos_nav/chat.png') }}" alt="Chat"></a>
</div>

@endsection

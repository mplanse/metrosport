@extends('layouts.nav')

@section('content')
{{-- <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}

<div id="app" class="container mt-4">
    <lliga :id= {{ $id }}></lliga>
</div>



@endsection

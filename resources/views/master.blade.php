@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $user = Auth::user();
    @endphp
    <div id="app">
        <app
            logged-in-user='{{ json_encode($user) }}'
        ></app>
    </div>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</div>
@endsection

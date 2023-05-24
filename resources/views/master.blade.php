@extends('layouts.app')

@section('content')
<div class="container">

    <div id="app">
        <app></app>
    </div>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</div>
@endsection

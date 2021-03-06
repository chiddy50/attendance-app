<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('css/sidemenu.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('css/all.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('css/layout.css') }}">
        <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
        <title>Attendance App</title>
    </head>
    <body>
        <div id="app">
            {{-- <navbar></navbar>
            <div class="container"> --}}
                <Home></Home>                
            {{-- </div> --}}
        </div>

    <script src="{{ secure_asset('js/app.js') }}"></script>
    <script src="{{ secure_asset('js/script.js') }}"></script>
    </body>
</html>

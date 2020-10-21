<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Main Url -->
    <meta name="main-url" content="{{ env('MAIN_URL') }}">

    <!-- API Base Url -->
    <meta name="base-url" content="{{ env('API_BASE_URL') }}">

    <!-- Admin Router Prefix -->
    <meta name="admin-router-prefix" content="{{ env('ADMIN_ROUTER_PREFIX') }}">

    <title>{{ config('app.name', 'Laravel') }}@hasSection('title') - @yield('title')@endif</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"
          integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <admin-main-page></admin-main-page>
    </div>
</body>
</html>

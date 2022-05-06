<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($title)) ? $title.' | '.\App\Http\Helpers\Helper::get_app_name() : \App\Http\Helpers\Helper::get_app_name()  }}</title>
    @stack('head')
    <link href="{{ asset('css/app.css?v=1.0.1') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <link rel="icon" href="{{ \App\Http\Helpers\Helper::get_favicon_link() }}"/>
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
        $UserHelper = new \App\Http\Helpers\FirefighterDashboardHelper();
        $route = \Illuminate\Support\Facades\Route::currentRouteName();
    @endphp
</head>
<body class="layout-fixed {{ str_replace('.','-',$route) }}">
<div id="app">
    @guest('firefighters')
        @include('partials.firefighter-navbar-guest')
    @else
        @include('partials.firefighter-navbar')
    @endguest

    @yield('modals')
    <main class="py-4">
        <div class="container-fluid">
            @guest('firefighters')
                <div id="app-content">
                    @yield('content')
                </div>
            @endguest

            @auth('firefighters')
                @include(isset($sidebar) ? $sidebar : 'partials.firefighter-sidebar')
                <div class="content-wrapper">
                    @yield('content')
                </div>
            @endauth
        </div>
    </main>
</div>
<script src="{{ asset('js/app.js?v=1.0.1') }}"></script>
@stack('js')
</body>
</html>
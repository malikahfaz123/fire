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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ \App\Http\Helpers\Helper::get_favicon_link() }}"/>
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
        $UserHelper = new \App\Http\Helpers\UserHelper();
        $route = \Illuminate\Support\Facades\Route::currentRouteName();
    @endphp
    <style>
        #load{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    background:url("/images/log.png") no-repeat center center rgba(0,0,0,0.25)
}
        </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div id="load"></div>
    <div id="contents">
         
    </div>

    <script>
        document.onreadystatechange = function () {
  var state = document.readyState
  if (state == 'interactive') {
       document.getElementById('contents').style.visibility="hidden";
  } else if (state == 'complete') {
      setTimeout(function(){
         document.getElementById('interactive');
         document.getElementById('load').style.visibility="hidden";
         document.getElementById('contents').style.visibility="visible";
      },1000);
  }
}
    </script>
<div id="app" class="wrapper">
    @guest
        @include('partials.navbar-guest')
    @else
        @include('partials.navbar')
    @endguest
    @yield('modals')
    <main class="py-4">
        <div class="content">
            @guest
                <div id="app-content" class="container-fluid mt-5">
                    @yield('content')
                </div>
            @endguest
        </div>
        <div class="content-wrapper">
            <div class="content">
                @auth
                    @include(isset($sidebar) ? $sidebar : 'partials.sidebar')
                    <div class="content-fluid">
                        @yield('content')
                    </div>
                @endauth
            </div>
        </div>
    </main>
</div>
<script src="{{ asset('js/app.js?v=1.0.1') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script src="{{ asset('js/select2.min.js') }}"> </script>
<script src="{{ asset('js/adminlte.js') }}"> </script>
<script src="{{ asset('js/adminlte.min.js') }}"> </script>

@stack('js')
<script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>

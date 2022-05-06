<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Confirmation</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
<div id="app">
    <main class="py-4">
        <div class="container-fluid">
            <div id="app-content">
                <div class="text-center mb-4">
                    <img class="header-logo" src="{{ \App\Http\Helpers\Helper::get_logo_link() }}" alt="{{ \App\Http\Helpers\Helper::get_app_name() }}">
                </div>
                <div class="col-md-6 ml-auto mr-auto p-4 bg-white shadow-sm text-center">
                    <h5 class="cambria-bold mb-3">Verification</h5>
                    <p class="card-text">{{ $message }}</p>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>

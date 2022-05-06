<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .bg-light{
            background-color: #f5f5f5 !important;
        }
    </style>
</head>
<body class="bg-light">
<div id="app">
    <main class="py-5">
        <div class="container-fluid">
            <div class="col-md-6 m-auto">
                @if(isset($firefighter->id) && $firefighter->id)
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="{{ \App\Http\Helpers\Helper::get_logo_link() }}" class="mb-4" alt="{{ \App\Http\Helpers\Helper::get_app_name() }}">
                                <h3 class="mb-4">Account Setup</h3>
                            </div>
                            <div id="response"></div>
                            <form id="add" novalidate>
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label class="required">Set Password</label>
                                    <input type="password" name="password" class="form-control">
                                    <div id="password" class="invalid-feedback"></div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="required">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control">
                                    <div id="confirm_password" class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary submit-btn btn-wd mr-3">Create Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="{{ \App\Http\Helpers\Helper::get_logo_link() }}" class="mb-4" alt="{{ \App\Http\Helpers\Helper::get_app_name() }}">
                                <h3 class="mb-4">Invalid Request</h3>
                            </div>
                            <p class="card-text text-center">The link has been expired or unrecognized.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@if(isset($firefighter->id) && $firefighter->id)
<script src="{{ asset('js/app.js') }}"></script>
<script>
    function formReset(){
        document.getElementById("add").reset();
    }
    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        console.log( $(this).serialize() );

        
        axios.post("{{ route('firefighter.setting.firefighter.reset-password',$firefighter->reset_password) }}",$(this).serialize()).then((response)=>{
            let html;
            if(response.data.status){
                formReset();
                html = `<div class="alert alert-success"><p class="mb-0">${response.data.msg}</p></div>`;
                setTimeout(function () {
                    window.location.href = '{{ route('firefighters.login') }}';
                },1000)
            }else{
                html = `<div class="alert alert-danger"><p class="mb-0">${response.data.msg}</p></div>`;
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            }
            $('#response').html(html);
        }).catch((error)=>{
            if(error.response.status === 422) {
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            }
        })

    });
</script>
@endif;
</body>
</html>

@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <span class="material-icons">multiple_stop</span>
            </a>
        </li>
    </ul>
    <div class="navbar-brand pl-1">
        <h5 class="mb-0"><a href="{{ route('dashboard') }}" class="text-dark">{{ \App\Http\Helpers\Helper::get_app_name() }}</a></h5>
    </div>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
            <li class="nav-item">
                <form class="form-inline mr-3" style="margin-top: 5px;">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <span class="material-icons">search</span>
                            </button>
                        </div>
                    </div>
                </form>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle no-carret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    @if($user->user_image)
                        <img width="35" src="{{ asset('storage/users/thumbnail/'.$user->user_image) }}" class="img-circle elevation-2" alt="User Image">
                    @else
                        <span class="material-icons" style="font-size: 30px;">account_circle</span>
                    @endif
                </a>
                <div id="top-nav-dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <div class="user-panel mt-2 pb-2 mb-2 d-flex border-bottom">
                        <div class="image">
                            @if($user->user_image)
                                <img src="{{ asset('storage/users/thumbnail/'.$user->user_image) }}" class="img-circle elevation-2" alt="User Image">
                            @else
                                <span class="material-icons" style="font-size: 40px;">account_circle</span>
                            @endif
                        </div>
                        <div class="info text-capitalize">
                            <div style="line-height: 17px;">{{ $UserHelper->get_user_full_name() }}</div>
                            <span style="font-size: 14px;" class="text-muted">{{ $user->role->name }}</span>
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{ route('user.profile') }}"><span class="material-icons text-success">perm_identity</span> My Profile</a>
                    @can('settings.read')
                        <a class="dropdown-item" href="{{ route('settings.index') }}"><span class="material-icons text-info">settings</span> Settings</a>
                    @endcan
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="material-icons text-danger">power_settings_new</span> {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>

@php $user = \Illuminate\Support\Facades\Auth::guard('firefighters')->user(); @endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <div class="navbar-brand pl-3">
        <h5 class="mb-0"><a href="{{ route('firefighters.dashboard') }}" class="text-dark">{{ \App\Http\Helpers\Helper::get_app_name() }}</a></h5>
    </div>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest('firefighters')
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
                    @if($user->firefighter_image)
                        <img width="35" src="{{ asset('storage/firefighter/'.$user->id.'/thumbnail/'.$user->firefighter_image) }}" class="img-circle elevation-2" alt="User Image">
                    @else
                        <span class="material-icons" style="font-size: 30px;">account_circle</span>
                    @endif
                </a>
                <div id="top-nav-dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <div class="user-panel mt-2 pb-2 mb-2 d-flex border-bottom">
                        <div class="image">
                            @if($user->firefighter_image)
                                <img src="{{ asset('storage/firefighter/'.$user->id.'/thumbnail/'.$user->firefighter_image) }}" class="img-circle elevation-2" alt="User Image">
                            @else
                                <span class="material-icons" style="font-size: 40px;">account_circle</span>
                            @endif
                        </div>
                        <div class="info text-capitalize">
                            <div style="line-height: 17px;">{{ $UserHelper->get_user_full_name() }}</div>
                            {{-- <span style="font-size: 14px;" class="text-muted">{{ $user->role->name }}</span> --}}
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{ route('firefighters.profile') }}"><span class="material-icons text-success">perm_identity</span> My Profile</a>
                    {{-- @can('settings.read') --}}
                        {{-- <a class="dropdown-item" href="{{ route('settings.index') }}"><span class="material-icons text-info">settings</span> Settings</a> --}}
                    {{-- @endcan --}}
                    <a class="dropdown-item" href="{{ route('firefighters.logout') }}" onclick="event.preventDefault(); document.getElementById('firefighters-logout-form').submit();">
                        <span class="material-icons text-danger">power_settings_new</span> {{ __('Logout') }}
                    </a>
                    <form id="firefighters-logout-form" action="{{ route('firefighters.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>
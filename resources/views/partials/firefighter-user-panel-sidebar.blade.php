@php
    $user = \Illuminate\Support\Facades\Auth::guard('firefighters')->user();
    $UserHelper =  new \App\Http\Helpers\FirefighterDashboardHelper();
@endphp
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        {{-- {{ dd($user)  }} --}}
        @if($user->firefighter_image)


            <img src="{{ asset('storage/firefighter/'.$user->id.'/thumbnail/'.$user->firefighter_image) }}" class="img-circle elevation-2" alt="User Image">
        @else
            <span class="material-icons text-muted" style="font-size: 40px;">account_circle</span>
        @endif
    </div>
    <div class="info pt-2 text-capitalize">
        <a href="{{ route('firefighters.profile') }}">{{ $UserHelper->get_user_full_name() }}</a>
    </div>
</div>
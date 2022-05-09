@php
    $user = isset($user) ? $user : \Illuminate\Support\Facades\Auth::user();
    $UserHelper = isset($UserHelper) ? $UserHelper : new \App\Http\Helpers\UserHelper();
@endphp
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        @if($user->user_image)
            <img src="{{ asset('storage/users/thumbnail/'.$user->user_image) }}" class="img-circle elevation-2" alt="User Image">
        @else
            <span class="material-icons text-muted" style="font-size: 40px;">account_circle</span>
        @endif
    </div>
    <div class="info pt-2 text-capitalize">
        <a href="{{ route('user.profile') }}">{{ $UserHelper->get_user_full_name() }}</a>
    </div>
</div>
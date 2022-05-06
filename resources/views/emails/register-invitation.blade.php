@component('mail::message')
<p>Hello {{ $user->name }},</p>

<p>You have been invited by {{ $host->name }} to join "{{ \App\Http\Helpers\Helper::get_app_name() }}". Please click the link below to setup your account.</p>

@component('mail::button', ['url' => route('verify.user_invitation',$user->reset_password)])
Account Setup
@endcomponent

Thanks,<br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
@endcomponent
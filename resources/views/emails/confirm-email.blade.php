@component('mail::message')
<h2>{{ $subject }}</h2>

<p>Dear {{ $f_name }},</p>
<p>Please click the button below to verify your email. If this has nothing to do with you, you can ignore this email.</p>

@component('mail::button', ['url' => $link])
Confirm Email
@endcomponent

Thanks,<br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
@endcomponent
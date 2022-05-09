@component('mail::message')
<p>Hello {{ $firefighter->f_name .' '. $firefighter->l_name }},</p>

<p>You have been invited by {{ $host->name }} to join "{{ \App\Http\Helpers\Helper::get_app_name() }}". Please click the link below to complete your registration.</p>

@component('mail::button', ['url' => route('verify.verify_firefighter_invitation',$firefighter->reset_password)])
Account Setup
@endcomponent

Thanks,<br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
@endcomponent

@component('mail::firefighter-message')
<p>Dear Candidate,</p>
<p>You have been invited to join the "{{ \App\Http\Helpers\Helper::get_app_name() }}".</p>
<p>
    To register yourself.
    <!-- @component('mail::link', ['url' => route('firefighters.login')]) Click Here @endcomponent -->
    @component('mail::link', ['url' => route('firefighters.register')]) Click Here @endcomponent
</p>


Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}

<br>
<p style="color: #008000; font-size: 12px;">
    Note : This is an auto-generated email, please do not reply.
</p>
@endcomponent
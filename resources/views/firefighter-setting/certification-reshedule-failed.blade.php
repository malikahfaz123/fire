@component('mail::firefighter-message')

<p>Dear {{ ucfirst($firefighter_name)}},</p>

<p>Thankyou for reattempting the test for Credential "{{ ucfirst($certification)}}". We really appreciate your efforts.</p>
<p>We regret to inform you that this time again, the odds weren’t in your favour and you’ve missed to meet a certain criteria to be awarded with the credential. </p>
<p>For further information, you can @component('mail::link', ['url' => route('firefighters.failed-certificates.index')]) Click Here @endcomponent </p>
<p>Wish you luck.</p>

Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
<br>
<p style="color: #008000; font-size: 12px;">
    Note : This is an auto-generated email, please do not reply.
</p>
@endcomponent
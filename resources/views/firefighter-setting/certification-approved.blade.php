@component('mail::firefighter-message')

<p>Dear {{ ucfirst($firefighter)}},</p>

<p>Thanks for submitting your request for credential "{{ ucfirst($certification) }}".</p>
<p>Your request has been approved and a test for "{{ ucfirst($certification) }}" is scheduled on {{ $test_date }} {{ $test_time }}.</p>
<p>Kindly ensure your availability on the scheduled date.</p>
<p>For further information, you can @component('mail::link', ['url' => route('firefighters.approved-certificates.index')]) Click Here @endcomponent.</p>
<p>Wish you luck.</p>

Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
<br>
<p style="color: #008000; font-size: 12px;">
    Note : This is an auto-generated email, please do not reply.
</p>
@endcomponent
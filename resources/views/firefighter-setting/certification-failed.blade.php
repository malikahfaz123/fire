@component('mail::firefighter-message')

<p>Dear {{ ucfirst($firefighter)}},</p>

<p>Thank you for appearing for the test of "{{ ucfirst($certification) }}" on {{ $test_date }} {{ $test_time }}. We really appreciate your interest and we want to thank you for the time and energy you invested.</p>
<p>We regret to inform you that your test result did not meet the criteria to be considered ‘Pass’ and be awarded with the respective certificate. <br><br></p>
<p>However, you can still reattempt the test and submit a request to reschedule the test by @component('mail::link', ['url' => route('firefighters.failed-certificates.index')]) Click Here @endcomponent, if you wish to do so.
<p>Once you submit a request to reattempt, you will receive an email with details regarding your retest.</p>
<p>Wish you luck for your future.</p>

Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
<br>
<p style="color: #008000; font-size: 12px;">
    Note : This is an auto-generated email, please do not reply.
</p>
@endcomponent
@component('mail::message')

<p>Dear {{ $firefighter->f_name }},</p>

<p>Thankyou for reattempting the test for Credential "{{ $certificate->title }}" on {{\App\Http\Helpers\Helper::date_format($update_certificate_status->test_date)}} {{$update_certificate_status->test_time}}. We really appreciate your efforts.</p>
<p>Congratulations! You have successfully passed the test for Credential "{{ $certificate->title }}".</p>
<p>For further information, you can @component('mail::link', ['url' => route('firefighters.awarded-certificates.index')]) Click Here @endcomponent </p>
<p>Wish you luck.</p>

Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}

<br>
<p style="color: #008000; font-size: 12px;">
    Note : This is an auto-generated email, please do not reply.
</p>
@endcomponent
@component('mail::message')

<p>Dear {{ $firefighter->f_name }},</p>

<p>Thank you for appearing in the test for "{{ $certificate->title }}" on {{$update_certificate_status->test_date}} {{$update_certificate_status->test_time}}. We really appreciate your interest and we want to thank you for the time and energy you invested.</p>
<p>Congratulations! We are pleased to inform you that you have successfully aced the test by your hard work and skills. You have been awarded with the credential that you can find attached to this email.</p>
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
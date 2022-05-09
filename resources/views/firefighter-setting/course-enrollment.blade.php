@component('mail::firefighter-message')

<p>Dear {{ ucfirst($firefighter)}},</p>
<p>
    We are pleased to inform you that you have been successfully enrolled in "{{ ucfirst($course) }}". We really appreciate your interest and wish you good luck for the future.
</p>
<p>
    For further information, to view your course. @component('mail::link', ['url' => route('firefighters.my-courses.index')]) Click Here @endcomponent 
</p>

Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}

<br>
<p style="color: #008000; font-size: 12px;">
    Note : This is an auto-generated email, please do not reply.
</p>
@endcomponent
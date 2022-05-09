@component('mail::message')
<p>Dear {{ $firefighter->f_name }},</p>

<p>Congratulations on completing your Course "{{ ucfirst($course->course_name) }} ({{ $course->prefix_id }})". </p>

<p>Please find your Transcript for the Course attached to this email. </p>

Regards,<br>
Team Kean FireSafety <br>
{{ \App\Http\Helpers\Helper::get_app_name() }}
@endcomponent
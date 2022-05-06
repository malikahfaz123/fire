@component('mail::message')
    <p>Dear {{$name}},</p>
    <p>This is to inform you that Mr/Mrs {{ ucfirst($assigned_by) }} has {{ $message }} {{ ucfirst($assigned_to) }}.</p><br>
    <br>Regards,
<br>Team Kean FireSafety
<br>{{ \App\Http\Helpers\Helper::get_app_name() }}
    <br><p style="color: #008000; font-size: 12px; text-align: center;">Note : This is an auto-generated email, please do not reply.</p>
@endcomponent

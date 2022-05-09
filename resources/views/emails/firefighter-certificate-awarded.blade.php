@component('mail::message')
<p>Hi Team,</p>
<p>A Credential "{{ $certificate->title }} ({{ $certificate->prefix_id }})" has been awarded to a student on {{ \App\Http\Helpers\Helper::date_format($issue_date) }}, with the following details:
<p>Student Name: {{ $firefighter->f_name }} {{ $firefighter->m_name }} {{ $firefighter->l_name }}</p>
<p>Email Address: {{ $firefighter->email }} </p>
<p>Phone No.:  {{ $firefighter->cell_phone  }} </p>

<br>
<p>{{ \App\Http\Helpers\Helper::get_app_name() }}</p>

@endcomponent
@component('mail::message')

<p>Hi Team,</p>
<p>A request to reschedule test for Credential "{{ ucfirst($certificate) }}" has been received, with the following details:</p>
<p>Student Name: {{ ucfirst($firefighter_f_name).' '.$firefighter_m_name.' '.$firefighter_l_name}}</p>

<p>Email Address: {{ ($firefighter_email) }}</p>
<p>Phone No.: {{ ($cell_phone) }}</p>

<p>To view the complete information, @component('mail::link', ['url' => route('certification.index')]) Click Here @endcomponent.</p>

{{ \App\Http\Helpers\Helper::get_app_name() }}
@endcomponent
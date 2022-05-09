@component('mail::message')
# Hello {{ ucfirst($firefighter->f_name.' '.$firefighter->m_name.' '.$firefighter->l_name) }}, <br>
<p>Your account has been created by {{ ucfirst($host->name) }} </p>

@if($firefighter->instructor_level!=null)
<p>You assigned as a Fire Instructor </p>
<p>Your Level : {{$firefighter->instructor_level}} </p>
@endif

<p>Type : {{$firefighter->type}} </p>

# Credentials <br>
<p>Email: {{ $firefighter->email }}</p>
<p>Password: 12345678</p>

@component('mail::button', ['url' => route('firefighters.login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

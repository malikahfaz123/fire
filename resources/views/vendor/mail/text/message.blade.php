@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ \App\Http\Helpers\Helper::get_logo_link() }}" alt="{{ \App\Http\Helpers\Helper::get_app_name() }}">
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ \App\Http\Helpers\Helper::get_app_name() }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent

@component('mail::message')
<br/>
<h2>{!!$title!!}</h2>

{!!$body!!}
<br/>

With Regards, 
{{ $app_settings['app_name'] }} Team
@endcomponent


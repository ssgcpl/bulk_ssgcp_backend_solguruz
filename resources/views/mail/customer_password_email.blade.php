@component('mail::message')
<h2>Hello {{$user->first_name}} </h2>
<br/>

Please find your updated password below:
<br/>

<h2 style="align-items: center;">{{$password}}</h2>
With Regards, 
{{ $app_settings['app_name'] }} Team
@endcomponent


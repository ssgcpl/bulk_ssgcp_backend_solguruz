@component('mail::message')
<h2>{{ trans('notifications.customer_password_reset_greeting', ['user' => $user->first_name]) }} </h2>
<br/>

{{ trans('notifications.customer_password_reset_message') }}
<br/>

<h2 style="align-items: center;">{{$verification_code}}</h2>

{{ trans('notifications.thanks') }},<br>
{{ $app_settings['app_name'] }} Team
@endcomponent

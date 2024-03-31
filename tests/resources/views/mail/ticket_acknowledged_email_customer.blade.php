@component('mail::message')
	Hi @if($user->first_name) {{$user->first_name.' '.$user->last_name}} @else Customer @endif<br/><br/>
	Thank you for contacting Sam Samyik Ghatna Chakra.<br/>
	This is just a quick note to inform you that we acknowledged your ticket and have already started working on resolving your query [Ticket ID: {{$ticket->id}} dated {{date('d-m-Y',strtotime($ticket->created_at))}}].
	<br/><br/>
	Admin Comments: {{$ticket->acknowledged_comment}}
 	<br/>
 	<br/> 
 	If you have any further questions or concerns, please let us know. We are available round-the-clock and always happy to help. Thanks for being a loyal customer.
 	<br/>
 	<br/>
  Thanks and Regards,<br/>
  SSGC customer support team<br/>
  Customer care no: {{$app_settings['customer_care_no']}}<br/>
  Email address: {{$app_settings['contact_email']}}
@endcomponent
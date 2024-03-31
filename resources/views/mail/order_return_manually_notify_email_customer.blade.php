@component('mail::message')
	Dear @if($user->first_name) {{$user->first_name.' '.$user->last_name}} @else Customer @endif<br/><br/>
 	We are glad to inform you that we have dispatched your order!<br/><br/>
	Your order has been packed with care by our people, and we are working diligently to deliver your order for Order Id: {{$order_return_note->order_return_id}}. <br/><br/>
	Please find below helpful information to track your order.<br/>
	Order Id: {{$order_return_note->order_return_id}}<br/>
	Transaction ID: {{$order_return_note->transaction_id}}<br/>
	Payment Status: {{$order_return_note->payment_status}}<br/>
	Notes : {{$customer_note}}
 	<br/>
 	<br/> 
  Thanks and Regards,<br/>
  SSGC customer support team<br/>
  Customer care no: {{$app_settings['customer_care_no']}}<br/>
  Email address: {{$app_settings['contact_email']}}
@endcomponent
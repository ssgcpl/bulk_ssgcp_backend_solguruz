@component('mail::message')
	Dear @if($user->first_name) {{$user->first_name.' '.$user->last_name}} @else Customer @endif<br/><br/>
 	Thank you for placing order with us! <br/><br/>
 	Your Order with Order {{$order->order_id}} worth {{$order->total_payable}} Rs. has been confirmed. Our team is working on your order and will deliver (for Physical Books) it soon.
 	<br/>
 	<br/>
  Thanks and Regards,<br/>
  SSGC BO customer support team<br/>
  Customer care no: {{$app_settings['customer_care_no']}}<br/>
  Email address: {{$app_settings['contact_email']}}
@endcomponent
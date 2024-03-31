@component('mail::message')
Dear Admin,<br>
	This order with {{$order->order_id}} has been placed but due to less OR no quantity OR Inactive/Unpublish book OR Product out of stock OR Duplicate order for digital product purchase has been rejected.<br>
	Payment of this order has been successful.<br>
	Please proceed with refund.<br>
	Please check details :<br>
	order Id : {{$order->id}}<br>
	amount : {{$order->total_payable}}<br>
	mode : {{$order->payment_type}}<br>
  Thanks and Regards,<br/>
  SSGC customer support team<br/>
  Customer care no: {{$app_settings['customer_care_no']}}<br/>
  Email address: {{$app_settings['contact_email']}}
  @endcomponent

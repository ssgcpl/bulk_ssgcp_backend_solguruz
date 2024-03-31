@component('mail::message')
<h2>Hello Admin, </h2>
<br/>

You have received a new enquiry. Please check the details below.

<br/>

<h2 style="align-items: center;"><b>Category: </b>{{$data['category']}}</h2>
<h2 style="align-items: center;"><b>First Name: </b>{{$data['first_name']}}</h2>
<h2 style="align-items: center;"><b>Last Name: </b>{{$data['last_name']}}</h2>
<h2 style="align-items: center;"><b>Email: </b>{{$data['email']}}</h2>
<h2 style="align-items: center;"><b>Company Name: </b>{{$data['company_name']}}</h2>
<h2 style="align-items: center;"><b>Subject: </b>{{$data['subject']}}</h2>
<h2 style="align-items: center;"><b>Message: </b>{{$data['message']}}</h2>
<br>
With Regards, <br>
{{ $app_settings['app_name'] }} Team
@endcomponent


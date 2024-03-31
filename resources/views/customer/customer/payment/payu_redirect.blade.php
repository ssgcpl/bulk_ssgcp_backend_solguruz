<html>
<head>
</head>
<body>
<form action='{{$data->payment_url}}' method='post' name="redirect">
	@csrf
	<input type="hidden" name="key" value="{{$data->key}}" />
	<input type="hidden" name="txnid" value="{{$data->txnid}}" />
	<input type="hidden" name="productinfo" value="{{$data->productinfo}}" />
	<input type="hidden" name="amount" value="{{$data->amount}}" />
	<input type="hidden" name="email" value="{{$data->email}}" />
	<input type="hidden" name="firstname" value="{{$data->firstname}}" />
	<input type="hidden" name="lastname" value="{{$data->lastname}}" />
	<input type="hidden" name="surl" value="{{$data->surl}}"/>
	<input type="hidden" name="furl" value="{{$data->furl}}" />
	<input type="hidden" name="phone" value="{{$data->phone}}" />
	<input type="hidden" name="hash" value="{{$data->hash}}" />
	<!-- <input type="submit" value="submit">  -->
</form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>
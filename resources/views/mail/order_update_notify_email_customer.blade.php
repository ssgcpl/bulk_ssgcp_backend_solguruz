@component('mail::message')
    <p>
    Dear @if($user->first_name) {{$user->first_name.' '.$user->last_name}} @else Customer @endif<br/><br/>
    @if($data['order_type'] == 'physical_books')
    We are glad to inform you that we have dispatched your order!<br/><br/>
    Your order has been packed with care by our people, and we are working diligently to deliver your order for Order Id: {{$data['order_id']}}. <br/><br/>
    Please find below helpful information to track your order.<br/>
    @else
    We are glad to inform you that your order has been executed successfully! <br>
    Please find the order details herewith <br>
    @endif
    </p>
    <table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="margin: 0 auto;text-align: center;padding: 10px 0;border: 2px solid #f1f1f1;">
            <tbody>
                <tr>
                    <td>
                        <table width="100%" style="padding: 10px 24px; border-bottom:1px solid #e2e2e2">
                            <tr>
                                <td style="text-align: left;width: 30%;vertical-align:middel;"> 
                                    <img src="{{asset('web_assets/images/logo.png')}}" width="100px" alt="Logo" /> 
                                </td>
                                <td style="text-align: left;padding: 0 0px;width: 65%;vertical-align:middel;"> 
                                    <h2 style="margin:5px 0;">Thanks for shopping with us</h2>  
                                </td> 
                            </tr>
                        </table>
                    </td>
                </tr> 
                <tr>
                    <td>
                        <table width="100%" style="border-bottom:1px solid #e2e2e2; padding:10px 0">
                            @if($data['billing_address'])
                            <tr>
                                <td style=" border-right: 1px solid #e2e2e2; text-align: left;padding: 0 24px;width: 50%;vertical-align:top;">
                                    <h3>Billing address</h3>
                                    <p style="margin:5px 0;"><b>{{$data['billing_address']['company_name']}}</b></p>
                                    <p style="margin:5px 0;"><b>{{$data['billing_address']['customer_name']}}</b></p>
                                    <p style="margin:5px 0;"><b>Address:</b>{{$data['billing_address']['address']}} </p> 
                                    <p style="margin:5px 0;"><b>State Name:</b>{{$data['billing_address']['state']}}</p>
                                    <p style="margin:5px 0;"><b>Email:{{$data['billing_address']['email']}}</b></p>
                                    <p style="margin:5px 0"><b>Mob.:{{$data['billing_address']['mobile']}}</b></p>
                                </td> 
                                <td style="text-align: left;padding: 0 24px;width: 50%;vertical-align:top;"> 
                                    <h3>Shipping address</h3>
                                     <p style="margin:5px 0;"><b>{{$data['shipping_address']['company_name']}}</b></p>
                                    <p style="margin:5px 0;"><b>{{$data['shipping_address']['customer_name']}}</b></p>
                                    <p style="margin:5px 0;"><b>Address:</b>{{$data['shipping_address']['address']}} </p> 
                                    <p style="margin:5px 0;"><b>State Name:</b>{{$data['shipping_address']['state']}}</p>
                                    <p style="margin:5px 0;"><b>Email:{{$data['shipping_address']['email']}}</b></p>
                                    <p style="margin:5px 0"><b>Mob.:{{$data['shipping_address']['mobile']}}</b></p>
                                </td>
                            @endif
                        </table>                    
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <table width="100%" style="padding:10px 0" >
                            <tr>
                                <td style="text-align: left;padding: 0 24px; width:100%;vertical-align:top;"> 
                                    <p style="margin:5px 0;">Hi {{$data['user_name']}},</p>
                                    <p style="margin:5px 0;">We have finished processing your order.</p>            
                                    <p style="margin:5px 0;"><b>Order ID:</b> #{{$data['order_id']}}</p>            
                                    <p style="margin:5px 0;"><b>Order Date:</b> ({{$data['dated']}})</p>  
                                </td>
                            </tr> 
                        </table>                    
                    </td>
                </tr>
                <tr>
                    <td  style="text-align: left;padding: 0 24px;width: 50%;vertical-align:top;">
                        <table width="100%" class="items_table" style="  border-collapse: collapse;margin: 0px 0 20px;" border="1" bgcolor="#ffffff"  cellpadding="10">
                            <thead>
                                <tr align="center" valign="top">
                                    <th class="items_table">Sr</th>
                                    <th class="items_table">Product</th>
                                    <th class="items_table">Qty</th>
                                    <th class="items_table">MRP<br/> (Per Item)</th>
                                    <th class="items_table">Rate after T.D</th> 
                                    <th class="items_table">Total after T.D</th> 
                                    <th class="items_table">Weight (per item)</th> 
                                    <th class="items_table">Total Weight (per item)</th>  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['order_items'] as $order_item)
                                    <tr>
                                        <td class="items_table" align="center">{{$order_item['id']}}</td> 
                                        <td class="items_table">{{$order_item['name']}}</td> 
                                        <td class="items_table" align="center">{{$order_item['quantity']}}</td> 
                                        <td class="items_table" align="right">{{$order_item['mrp']}}</td> 
                                        <td class="items_table" align="right">{{$order_item['rate']}}</td> 
                                        <td class="items_table" align="right">₹{{$order_item['total']}} </td> 
                                        <td class="items_table" align="right">{{$order_item['weight']}}</td> 
                                        <td class="items_table" align="right">{{$order_item['total_weight']}}</td>  
                                    </tr>
                                @endforeach
                                <tr>                                
                                    <td class="items_table" align="center" colspan="8"><b>Total Weight: {{$data['total_weight']}}(Kg) Bundles: {{$data['bundles']}}</b></td> 
                                </tr>
                                <tr>                                
                                    <td class="items_table" colspan="7"><b>Subtotal:</b></td> 
                                    <td class="items_table" align="right"><b>₹{{$data['total_sale_price']}}</b></td>
                                </tr> 
                                <tr>                                
                                    <td class="items_table" colspan="7"><b>Shipping:</b></td> 
                                    <td class="items_table" align="right"><b>₹{{$data['delivery_charges']}}</b></td>
                                </tr>
                                <tr>                                
                                    <td class="items_table" colspan="7"><b>Payment method:</b></td> 
                                    <td class="items_table" align="right">{{$data['payment_type']}}</td>
                                </tr>
                                <tr>                                
                                    <td class="items_table" colspan="7"><b>Total:</b></td> 
                                    <td class="items_table" align="right"><b>₹{{$data['total_amount']}}</b></td>
                                </tr>  
                            </tbody>
                        </table>
                    </td>                
                </tr> 
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td style="text-align: center; padding: 0 24px;width: 50%;vertical-align:top;">  
                                    <p style="margin:5px 0;">
                                        सम-सामयिक घटना चक्र प्रकाशन <br/>
                                        188ए/128, एलनगंज, चर्चलेन, इलाहाबाद-211002 <br/>
                                        फ़ोन : (0532) 2465524, 2465525 <br/>
                                        Mobile : 9389409346, 9696431507 <br/>
                                        Website: https://bo.ssgcp.com
                                    </p>  
                                </td>
                            </tr> 
                        </table>                    
                    </td>
                </tr>
            </tbody>
        </table>
  <p>
  Thanks and Regards,<br/>
  SSGC customer support team<br/>
  Customer care no: {{$app_settings['customer_care_no']}}<br/>
  Email address: {{$app_settings['contact_email']}}
</p>
@endcomponent
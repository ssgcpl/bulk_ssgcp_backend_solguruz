<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order Invoice</title>

    <style type="text/css">
       
    table.items_table { border-left: 0.01em solid #ccc;  border-right: 0; border-top: 0.01em solid #ccc; border-bottom: 0;    border-collapse: collapse;}
    table.items_table td,
    table.items_table th { border-left: 0; border-right: 0.01em solid #ccc; border-top: 0;border-bottom: 0.01em solid #ccc; 
    }
     .page-break {
        page-break-after: always;
    }
    </style>
</head>

<body style="margin: 0; padding: 20px; -webkit-print-color-adjust: exact !important;">
    @foreach($data_array as $key=>$data)
    @if($key > 0)
        <div class="page-break"></div>
    @endif
    @if(count($data['order_items']) > 20)
        <table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="width: 1000px;margin: 0 auto;text-align: center;padding: 5px 0;border: 2px solid #f1f1f1; font-size: 42px; font-family: 'Arabic', sans-serif;page-break-inside:avoid;">
    @else
        <table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="width: 1000px;margin: 0 auto;text-align: center;padding: 10px 0;border: 2px solid #f1f1f1; font-size: 16px; font-family: 'Arabic', sans-serif;page-break-inside:avoid;">
    @endif
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
                            <tr>
                                @if($data['billing_address'])
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
                        <table width="100%" class="items_table" style=" border-collapse: collapse;margin: 0px 0 20px;" border="1" bgcolor="#ffffff"  cellpadding="10">
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
                                @foreach($data['order_items'] as $k=>$order_item)
                                    @if($order_item['id'] == 20)
                                        <tr style="page-break-before: always !important;">
                                    @else
                                        <tr>
                                    @endif
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
                        </table>
                    </td>                
                </tr> 
                <tr>
                    <td>
                        <table width="100%" >
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
    @endforeach
</body>

</html>
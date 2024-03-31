<!doctype html>
<html>


<head>
    <meta charset="utf-8">
    <title>Stock Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
 
    <style type="text/css">
      body { font-family : 'Arabic'; }
      table.items_table { border-left: 0.01em solid #ccc;  border-right: 0; border-top: 0.01em solid #ccc; border-bottom: 0;    border-collapse: collapse;  }
      table.items_table td,
      table.items_table th { border-left: 0; border-right: 0.01em solid #ccc; border-top: 0;border-bottom: 0.01em solid #ccc;
    }
    table, td, tr {}
    @page {
        margin: 20px;
    }
    </style>
</head>

<body pdfEncoding="Identity-H" style="margin: 0; font-size: 12px; padding: 20px 0; -webkit-print-color-adjust: exact !important; ">
  <table border="0" bgcolor="#ffffff" cellpadding="10" style="width: 100%; margin: 0 auto;text-align: center;padding: 0px 0;">
       <thead>
          <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Sku ID</th>
            <th>Stock Status</th>
            <th>MRP</th>
            <th>Dealer Sale Price</th>
            <th>Retailer Sale Price</th>
          </tr>
        </thead>
        <tbody>
            @foreach($data_array as $stock_report)
                <tr>
                    <td align="center">{{$stock_report['id']}}</td> 
                    <td>{!!$stock_report['heading']!!}</td> 
                    <td>{!!$stock_report['sku_id']!!}</td> 
                    <td>{!!ucfirst(str_replace('_',' ',$stock_report['stock_status']))!!}</td> 
                    <td>{!!$stock_report['mrp']!!}</td> 
                    <td>{!!$stock_report['dealer_sale_price']!!}</td> 
                    <td>{!!$stock_report['retailer_sale_price']!!}</td> 
                </tr>
             @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Sku ID</th>
            <th>Stock Status</th>
            <th>MRP</th>
            <th>Dealer Sale Price</th>
            <th>Retailer Sale Price</th>
          </tr>
        </tfoot>
  </table>
</body>
</html>
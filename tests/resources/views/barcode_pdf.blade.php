<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Barcode</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
     
</head>
<style type="text/css">
    body { font-family :  serif; }
    table.items_table {
    border-left: 0.01em solid #ccc;
    border-right: 0;
    border-top: 0.01em solid #ccc;
    border-bottom: 0;
    border-collapse: collapse;
    }
    table.items_table td,
    table.items_table th {
        border-left: 0;
        border-right: 0.01em solid #ccc;
        border-top: 0;
        border-bottom: 0.01em solid #ccc;
    }
</style>

<body style="margin: 0; font-size: 12px; font-family:  sans-serif; padding: 20px; -webkit-print-color-adjust: exact !important;">
    <h3>Barcode Details</h3>
    <table border="0" bgcolor="#ffffff" cellspacing="10" cellpadding="10" style="width: 100%;margin: 0 auto;text-align: center; padding: 10px 0;border: 2px solid #f1f1f1;">
        <thead>
          <tr>
            <th>ID</th>
            <th>Product Title</th>
            <th>Unique Code</th>
            <th>Image</th>
          </tr>
        </thead>
        <tbody>
            @foreach($data_array as $barcode)
                <tr>
                    <td align="center">{{$barcode['id']}}</td> 
                    <td>{!!$barcode['product_title']!!}</td> 
                    <td>{!!$barcode['barcode_value']!!}</td> 
                    <td align="center"><img src ="{!!asset($barcode['barcode_image'])!!}"/></td> 
                </tr>
             @endforeach
         </tbody>
          <tfoot>
          <tr>
            <th>ID</th>
            <th>Product Title</th>
            <th>Unique Code</th>
            <th>Image</th>
          </tr>
        </tfoot>
    </table>
</body>

</html>
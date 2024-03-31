<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Orders Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during admin side bar items for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    // title
    'plural' => 'Order Return',
    'heading' => 'Order Return',
    'admin_heading'  => 'Order Return Management',
    'title' => 'Order Return List',
    'add_new' => 'Add New',
    'details' => 'Order Return Details',
    'update'  =>  'Update Order Return',
    'show'   => 'Show Order Return',
    'create' =>'Create Order Return',
    'view_product'=>'View Return Product Details',
    'product_detail'=>'Return Product Details',

    //atrributes
    'company_name' => 'Company Name',
    'order_return_status' => 'Return Order Status',
    'number'=>'Number',
    'created_date' =>'Date & Time',
    'total_price'=>'Total Price',
    'user_type'=>'User Type',
    'dealer'=>'Dealer',
    'retailer'=>'Retailer',
    'product_name'=>'Product Name',
    'product_image'=>'Product Image',
    'mrp'=>'MRP',
    'sale_price'=>'Sale Price',
    'ordered_qty'=>'Ordered Qty',
    'returnable_qty'=>'Returnable Qty',
    'returned_qty'=>'Returned Qty',
    'refused_qty'=>'Refused Qty',
    'accepted_qty'=>'Accepted Qty',
    'refundable_amount'=>'Refundable Amount',
    'weight'=>'Weight in KG',
    'stock'=>'Stock',

    //Status
    'return_placed' => 'Return placed',
    'dispatched'=> 'Dispatched',
    'in_review' => 'In Review',
    'rejected'=> 'Rejected',
    'accepted' => 'Accepted',
    'added' => 'Order Return Added Successfully',
    'deleted'=>'Order Return Deleted Successfully',


    //API
    'already_returned' => 'The selected item is already returned before.',
    'order_not_completed' => "The order is not completed yet,the item can't be returned.",
    'already_added_in_cart' => 'The item is already in the return cart.',
    'quantity_above_return_limit' => 'The selected quantity is not allowed,please select quantity under the given limit.',
    'quantity_above_purchase_limit' => 'The selected quantity is not allowed,please select quantity under the purchased limit.',
    'item_added_to_cart' => 'The item is added into the return cart.',
    'add_item_error' => "Something went wrong,coudn't add item into the return cart",
    'cart_create_error' => "Something went wrong,coudn't create return cart",
    'item_not_found' => "The item is not available.",
    'cart_not_found' => "The return cart is not available.",
    'cart_cleared' => "Return cart is empty",
    'item_removed' => 'The item is removed from the cart.',
    'quantity_update_error' => "Something went wrong,coudn't update the quantity.",
    'cart_is_empty' => 'Return cart is empty.',
    'order_return_success' => 'Order returned successfully.',
    'order_return_error' => "Something went wrong,coudn't return order.",
    'new_cart_error' => "Something went wrong,coudn't create new return cart for the selcted items.",
    'return_order_list_success' => 'Return orders list.',
    'return_order_list_empty' => 'Return orders empty.',
    'order_return_dispatch_success' => 'Order dispatched.',
    'order_return_dispatch_error' => "Something went wrong,coudn't dispatch the order.",
    'order_return_details' => 'Return order details.',

 ];

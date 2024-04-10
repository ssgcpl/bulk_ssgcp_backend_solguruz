<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class MyWishReturnListResource extends JsonResource
{   
    use CommonHelper;   
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   

        
        $user = Auth::guard('api')->user();
        $sale_price = $this->product->get_price($user) * $this->wish_return_qty;
       
        return [
            'wish_return_id'     => $this->id ? (string)$this->id : '',
            'book_id'     => $this->product_id ? (string)$this->product_id : '',
            'name'        => $this->product ? (string)$this->product->get_name() : '',
            'image'       => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            'sale_price'  => $sale_price ? (string)number_format($sale_price,2) :'0',
            'quantity' => $this->wish_return_qty ? (string)$this->wish_return_qty :'0',
            'description' => $this->description ? (string)$this->description :'',
            'dealer_id' => $this->dealer_id ? (string)$this->dealer_id :'',
            'dealer_name' => $this->dealer ? (string)$this->dealer->first_name :'',
           
        ];
    }
}

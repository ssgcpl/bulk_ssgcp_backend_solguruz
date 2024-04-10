<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Http\Resources\Customer\BookImageResource;
use App\Models\Helpers\CommonHelper;


class TrendingBookResource extends JsonResource
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

       
        return [
            'book_id'       => $this->product ? (string)$this->product->id : '' ,
            'name'          => $this->product ? (string)$this->product->get_name() :'', 
            'sale_price'    => $this->product ? $this->product->price ? (string)number_format($this->product->price,2) : '' :'',
            'mrp'           => $this->product ? $this->product->mrp ? (string)number_format($this->product->mrp,2) : '' :'' ,
            'image'         => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            'quantity'      => $this->product ? $this->product->quantity ? (string)$this->product->quantity : '0' :'0' ,
            'added_to_cart' => $this->product ? $this->product->added_to_cart ? (string)$this->product->added_to_cart : '0' :'0' ,
            'cart_item_id'  => $this->product ? $this->product->cart_item_id ? (string)$this->product->cart_item_id :'' :'',
           
        ];
    }
}

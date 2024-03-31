<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class ReturnCartResource extends JsonResource
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

        //Select display language
        if($this->product->language == 'english'){
           $name = $this->product ? $this->product->name_english ? (string)$this->product->name_english : '' : '';
        }else{
            
           $name = $this->product ? $this->product->name_hindi ? (string)$this->product->name_hindi : '' :''; 
        }
       
        return [
            'return_item_id' => $this->id ? (string)$this->id : '',
            'product_id'     => $this->product_id ? (string)$this->product_id : '',
            'order_return_id'     => $this->order_return_id ? (string)$this->order_return_id : '',
            'name'        => (string)$name, 
            'sale_price'  => $this->sale_price ? (string)floor($this->sale_price) : '',
            'total_sale_price'  => $this->total_sale_price ? (string)floor($this->total_sale_price) : '',
            'quantity'    => $this->total_quantity ? (string)$this->total_quantity :'',
            'purchased_qty'    => $this->order_item ? $this->order_item->supplied_quantity ? (string)$this->order_item->supplied_quantity :'' :'',
            'max_returnable_qty'    => $this->product ? $this->product->last_returnable_qty ? (string)$this->product->get_max_return_quantity($this->order_item->supplied_quantity) :'' :'',
            'image'       => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            
           
        ];
    }
}

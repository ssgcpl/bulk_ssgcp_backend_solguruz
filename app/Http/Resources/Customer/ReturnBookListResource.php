<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class ReturnBookListResource extends JsonResource
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
        // echo "<pre>";print_r($this->product->language);exit;

        //Select display language
        if($this->lang == 'english' || $this->product->language == 'english'){
           $name = $this->product ? $this->product->name_english ? (string)$this->product->name_english : '' : '';
        }else{
            
           $name = $this->product ? $this->product->name_hindi ? (string)$this->product->name_hindi : $this->product->name_english :''; 
        }
       
        return [
            'language' => $this->language,
            'order_item_id' => $this->id ? (string)$this->id : '',
            'book_id'     => $this->product_id ? (string)$this->product_id : '',
            'order_id'     => $this->order_id ? (string)$this->order_id : '',
            'name'        => (string)$this->book_name ?? (string)$name, 
            'sale_price'  => $this->sale_price ? (string)number_format($this->sale_price,2) : '',
            'mrp'         => $this->mrp ? (string)number_format($this->mrp,2) : '',
            'quantity'    => $this->supplied_quantity ? (string)$this->supplied_quantity :'',
            'max_returnable_qty'    => $this->product ? $this->product->last_returnable_qty ? (string)$this->product->get_max_return_quantity($this->supplied_quantity) :'' :'',
            'image'       => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            'added_to_cart' =>  $this->added_to_cart ? (string)$this->added_to_cart : '0' ,
           
        ];
    }
}

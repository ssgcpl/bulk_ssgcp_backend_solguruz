<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class ReturnOrderItemList extends JsonResource
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
            'name' => $name,
            'image'       => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            'total_quantity'    => $this->total_quantity ? (string)$this->total_quantity :'',
            'total_sale_price'    => $this->total_sale_price ? (string)number_format($this->total_sale_price,2) :'',
            'accepted_quantity'    => $this->accepted_quantity ? (string)$this->accepted_quantity :'',
            'rejected_quantity'    => $this->rejected_quantity ? (string)$this->rejected_quantity :'',
            'return_sale_price'    => $this->return_sale_price ? (string)number_format($this->return_sale_price,2) :'',
            
           
        ];
    }
}

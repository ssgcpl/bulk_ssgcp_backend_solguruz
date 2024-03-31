<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Http\Resources\Customer\BookImageResource;
use App\Models\Helpers\CommonHelper;


class SuggestionBookResource extends JsonResource
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
        if($this->lang == 'english' || $this->language == 'english'){
           $name = $this->name_english ? (string)$this->name_english : '';
           $description = $this->description_english ? (string)$this->description_english : '';
        }else{
            
           $name = $this->name_hindi ? (string)$this->name_hindi : ''; 
           $description = $this->description_hindi ? (string)$this->description_hindi : ''; 
        }
    
        return [
            'book_id'     => $this->id ? (string)$this->id : '' ,
            'name'        => (string)$name, 
            'sale_price'  => $this->price ? (string)floor($this->price) : '',
            'mrp'         => $this->mrp ? (string)floor($this->mrp) : '' ,
            'image'       => $this->image ? (string)asset($this->image) : '',
            'description'          => $description,
            'quantity'    => $this->quantity ? (string)$this->quantity : '0' ,
            'added_to_cart' => $this->added_to_cart ? (string)$this->added_to_cart : '0' ,
            'cart_item_id' => $this->cart_item_id ? (string)$this->cart_item_id :'',
           
        ];
    }
}

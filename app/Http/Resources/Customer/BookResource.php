<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Http\Resources\Customer\BookImageResource;
use App\Models\Helpers\CommonHelper;


class BookResource extends JsonResource
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
        }else{
            
           $name = $this->name_hindi ? (string)$this->name_hindi : ''; 
        }
        $user  = Auth::guard('api')->user();
        $data =  [
            'book_id'     => $this->id ? (string)$this->id : '' ,
            'name'        => (string)$name, 
            //'sale_price'  => $this->price ? (string)number_format($this->price,2) : '',
            'mrp'         => $this->mrp ? (string)number_format($this->mrp,2) : '' ,
            'image'       => $this->image ? (string)asset($this->image) : '',
            'visible_to'     => $this->visible_to ? (string)$this->visible_to : '' ,
            'quantity'    => $this->quantity ? (string)$this->quantity : '0' ,
            'added_to_cart' => $this->added_to_cart ? (string)$this->added_to_cart : '0' ,
            'cart_item_id' => $this->cart_item_id ? (string)$this->cart_item_id :'',
           
        ];
        if($user){
            $data['sale_price']  = $this->price ? (string)number_format($this->price,2) : '';
        }
        return $data;
    }
}

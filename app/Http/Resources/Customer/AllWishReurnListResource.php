<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class AllWishReurnListResource extends JsonResource
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
        
        $exist_under_dealer = '0';
        if(count($user->dealers) > 0)
        {
            $exist_under_dealer = '1';
        }

        //Select display language
        if($this->lang == 'english' || $this->language == 'english'){
           $name = $this->name_english ? (string)$this->name_english : '';
        }else{
            
           $name = $this->name_hindi ? (string)$this->name_hindi : ''; 
        }

        return [
            'book_id'            => $this->id ? (string)$this->id : '',
            'name'               => $name,
            'image'              => $this->image ? (string)asset($this->image) : '',
            'sale_price'         => (string)number_format($this->get_price($user),2),
            'mrp'                => $this->mrp ? (string)number_format($this->mrp,2) : '',
            'exist_under_dealer' => $exist_under_dealer
        
        ];
    }
}

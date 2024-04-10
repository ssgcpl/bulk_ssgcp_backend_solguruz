<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class DealerWishlistRequestDetailsResource extends JsonResource
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

        // $user = Auth::guard('api')->user();
        $address = $this->wish_list ? $this->wish_list->user ? $this->wish_list->user->address ? $this->wish_list->user->address :'':'':'';
        if($address){
            $state = $address->state ? (string)$address->state :'';
            $city = $address->city ? (string)$address->city :'';
            $postcode = $address->postcode ? (string)$address->postcode :'';
            $area = $address->area ? (string)$address->area :'';
            $house_no = $address->house_no ? (string)$address->house_no :'';
            $street = $address->street ? (string)$address->street :'';
            $landmark = $address->landmark ? (string)$address->landmark :'';
        }else{
            $state = '';
            $city = '';
            $postcode = '';
            $area = '';
            $house_no = '';
            $street = '';
            $landmark = '';
        }
       
        return [
            'wish_list_request_id' => $this->id ? (string)$this->id : '',
            'retailer_name'        => $this->wish_list ? $this->wish_list->user ? (string)$this->wish_list->user->company_name :'' :'',
            'full_name'            => $this->wish_list ? $this->wish_list->user ? (string)$this->wish_list->user->first_name.' '.$this->wish_list->user->last_name :'' :'',
            'date'                 => $this->wish_list ? $this->wish_list->created_at->format('d-m-Y') :'',
            'time'                 => $this->wish_list ? $this->wish_list->created_at->format('H:i a') :'',
            'created_at'           => $this->wish_list ? $this->wish_list->created_at :'',
            'quantity'             => $this->wish_list ? (string)$this->wish_list->wish_product_qty :'',
            'product_name'         => $this->wish_list ? $this->wish_list->product ? (string)$this->wish_list->product->get_name() :'' :'',
            'product_image'        => $this->wish_list ? $this->wish_list->product ? (string)asset($this->wish_list->product->image) :'' :'',
            'mrp'                  => $this->wish_list ? $this->wish_list->product ? (string)number_format($this->wish_list->product->mrp,2) :'' :'',
            'sale_price'           => $this->wish_list ? $this->wish_list->product ? (string)number_format($this->wish_list->product->get_price($this->wish_list->user),2) :'' :'',
            'contact_number'       => $this->wish_list ? (string)$this->wish_list->user->mobile_number : "",
            'country_code'         => $this->wish_list ? (string)$this->wish_list->user->country->country_code : "",
            'state'                => $state,
            'city'                 => $city,
            'postcode'             => $postcode,
            'area'                 => $area,
            'house_no'             => $house_no,
            'street'               => $street,
            'landmark'             => $landmark,
            
        ];
    }
}

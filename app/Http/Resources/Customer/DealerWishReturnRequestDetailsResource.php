<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class DealerWishReturnRequestDetailsResource extends JsonResource
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
        $address = $this->user ? $this->user->address ? $this->user->address :'':'';
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
            'wish_return_id' => $this->id ? (string)$this->id : '',
            'retailer_name'  => $this->user ? (string)$this->user->company_name :'',
            'full_name'      => $this->user ? (string)$this->user->first_name.' '.$this->user->last_name :'',
            'date'           => $this->created_at ? $this->created_at->format('d-m-Y') :'',
            'time'           => $this->created_at ? $this->created_at->format('H:i a') :'',
            'created_at'     => $this->created_at ? $this->created_at :'',
            'quantity'       => $this->wish_return_qty ? (string)$this->wish_return_qty :'',
            'product_name'   => $this->product ? (string)$this->product->get_name() :'',
            'product_image'  => $this->product ? (string)asset($this->product->image) :'',
            'mrp'            => $this->product ? (string)floor($this->product->mrp) :'',
            'sale_price'     => $this->product ? (string)floor($this->product->get_price($this->user)) :'',
            'contact_number' => $this->user ? (string)$this->user->mobile_number : "",
            'country_code'   => $this->user ? (string)$this->user->country->country_code : "",
            'state'          => $state,
            'city'           => $city,
            'postcode'       => $postcode,
            'area'           => $area,
            'house_no'       => $house_no,
            'street'         => $street,
            'landmark'       => $landmark,
            
        ];
    }
}

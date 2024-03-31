<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class RetailerDealerlistResource extends JsonResource
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
            'dealer_id'   => $this->dealer_id ? (string)$this->dealer_id : '',
            'first_name'        => $this->dealer ? $this->dealer->first_name ? (string)$this->dealer->first_name : '' : '',
            'profile_image'  => $this->dealer ? $this->dealer->profile_image ? (string)asset($this->dealer->profile_image) : (string)asset('customer_avtar.jpg') : (string)asset('customer_avtar.jpg'),

        ];
    }
}

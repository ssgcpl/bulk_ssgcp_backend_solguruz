<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class DealerWishReturnRequestResource extends JsonResource
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
       
        return [
            'wish_return_id'     => $this->id ? (string)$this->id : '',
            'retailer_name'        => $this->user ? (string)$this->user->company_name :'',
            'product_name'        =>$this->product ? (string)$this->product->get_name() :'',
            'date'        => $this->created_at ? $this->created_at->format('d-m-Y') :'',
            'time'        => $this->created_at ? $this->created_at->format('H:i a') :'',
            'created_at'        => $this->created_at ? $this->created_at :'',
            'quantity' => $this->wish_return_qty ? (string)$this->wish_return_qty :'',
            
        ];
    }
}

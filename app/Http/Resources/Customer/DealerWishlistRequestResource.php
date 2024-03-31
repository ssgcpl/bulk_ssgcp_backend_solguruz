<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class DealerWishlistRequestResource extends JsonResource
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
            'wish_list_request_id'     => $this->id ? (string)$this->id : '',
            'retailer_name'        => $this->wish_list ? $this->wish_list->user ? (string)$this->wish_list->user->company_name :'' :'',
            'product_name'        => $this->wish_list ? $this->wish_list->product ? (string)$this->wish_list->product->get_name() :'' :'',
            'date'        => $this->wish_list ? $this->wish_list->created_at->format('d-m-Y') :'',
            'time'        => $this->wish_list ? $this->wish_list->created_at->format('H:i a') :'',
            'created_at'        => $this->wish_list ? $this->wish_list->created_at :'',
            'quantity' => $this->wish_list ? (string)$this->wish_list->wish_product_qty :'',
            
        ];
    }
}

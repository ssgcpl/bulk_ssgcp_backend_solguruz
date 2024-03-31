<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;
use App\Http\Resources\Customer\RetailerDealerlistResource;


class WishlistDetailsResource extends JsonResource
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
            'wish_list_id'     => $this->id ? (string)$this->id : '',
            'book_id'     => $this->product_id ? (string)$this->product_id : '',
            'name'        => $this->product ? (string)$this->product->get_name() : '',
            'image'       => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            'sale_price'  => $this->product ? (string)floor($this->product->get_price($user)) :'',
            'mrp'         => $this->product ? $this->product->mrp ? (string)floor($this->product->mrp) : '' : '',
            'quantity' => $this->wish_product_qty ? (string)$this->wish_product_qty :'',
            'sub_heading'        => $this->product ? (string)$this->product->get_sub_heading() : '',
            'weight' => $this->product ? $this->product->weight ? (string)$this->product->weight : '' : '',
            'status_label' => (string)trans('wish_list.'.$this->product->stock_status),
            'dealers' => $this->dealers ? RetailerDealerlistResource::collection($this->dealers) : array(),
           
        ];
    }
}

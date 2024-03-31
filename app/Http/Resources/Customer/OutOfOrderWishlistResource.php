<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class OutOfOrderWishlistResource extends JsonResource
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
       
        return [
            'book_id'            => $this->id ? (string)$this->id : '',
            'name'               => (string)$this->get_name(),
            'sub_heading'        => (string)$this->get_sub_heading(),
            'weight'             => $this->weight ? (string)$this->weight : '',
            'image'              => $this->image ? (string)asset($this->image) : '',
            'sale_price'         => (string)floor($this->get_price($user)),
            'mrp'                => $this->mrp ? (string)floor($this->mrp) : '',
            'stock_status'       => $this->stock_status ? (string)$this->stock_status :'',
            'status_label'       => (string)trans('wish_list.out_of_stock'),
            'quantity'           => $this->quantity ? (string)$this->quantity :'0',
            'in_wishlist'        => $this->in_wishlist ? (string)$this->in_wishlist :'',
            'wish_list_id'       => $this->wish_list_id ? (string)$this->wish_list_id :'',
            'exist_under_dealer' => $exist_under_dealer
        ];
    }
}

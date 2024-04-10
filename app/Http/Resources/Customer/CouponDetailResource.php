<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;
use App\Http\Resources\Customer\CouponCoverImageResource;


class CouponDetailResource extends JsonResource
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
            'sub_coupon_id' => $this->id ? (string)$this->id : '' ,
            'name'          => (string)$this->coupon->name,
            'sale_price'    => (string)number_format($this->get_price($user),2),
            'mrp'           => $this->mrp ? (string)number_format($this->mrp,2) : '' ,
            'type'          => $this->coupon ? (string)trans('coupons.'.$this->coupon->item_type) : '',
            'expiry_date'   => $this->coupon ?(string) date('d-m-Y',strtotime($this->coupon->end_date)) : '' , 
            'description'   => $this->description,
            'cover_image'   => CouponCoverImageResource::collection($this->cover_images),
            'quantity'      => $this->quantity ? (string)$this->quantity : '0' ,
            'added_to_cart' => $this->added_to_cart ? (string)$this->added_to_cart : '0' ,
            'cart_item_id'  => $this->cart_item_id ? (string)$this->cart_item_id :'',
        ];
    }
}

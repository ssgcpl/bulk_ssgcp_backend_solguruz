<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Helpers\CommonHelper;
use DB;
use Auth;

class CouponCartItemResource extends JsonResource
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
        $item = $this->coupon;
        $user = Auth::guard('api')->user();
        if($user) {
            $cover_image = $item ? asset($item->image) : '';
            $name        = $item->coupon->name ? (string)$item->coupon->name : '';
            $type        = $item->coupon ? (string)trans('coupons.'.$item->coupon->item_type) : '';
            $end_date    = $item->coupon->end_date ? (string)date('d-m-Y',strtotime($item->coupon->end_date)) : '';
            $description = $item->description ? (string)$item->description : '';
            $sale_price  = $item->get_price($user);
        }

        return [
            'cart_item_id' => $this->id ? (string)$this->id : '' ,
            'quantity'     => $this->supplied_quantity ? (string)$this->supplied_quantity : '' ,
            'coupon_id'    => isset($item) ? (string)$item->id : '' ,
            'name'         => $name,
            'description'  => $description,
            'mrp'          => isset($item) ? (string)number_format($item->mrp,2) : '' ,
            'sale_price'   => (string)number_format($sale_price,2),
            'cover_image'  => $cover_image ? $cover_image : '',
            'type'         => $type,
            'expiry_date'  => $end_date ? $end_date : ''
        ];
    }
}

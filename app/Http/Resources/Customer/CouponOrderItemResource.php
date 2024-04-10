<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Helpers\CommonHelper;
use DB;
use Auth;

class CouponOrderItemResource extends JsonResource
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
            $sale_price  = $item->sale_price;
            if($this->order->order_status != 'refunded' &&  $this->order->order_status != 'cancelled') 
            {
                $available_coupons = $this->order_coupon_qr_codes->where('status','available');
                $available_coupons = OrderCouponQrCodeResource::collection($available_coupons);
            }else{
	        $available_coupons = [];
	    }
         
            $sold_coupons = $this->order_coupon_qr_codes->where('status','sold');
            $sold_coupons = OrderCouponQrCodeResource::collection($sold_coupons);

        }

        return [
            'order_item_id'     => $this->id ? (string)$this->id : '' ,
            'quantity'          => $this->supplied_quantity ? (string)$this->supplied_quantity : '' ,
            'coupon_id'         => isset($item) ? (string)$item->id : '' ,
            'name'              => $name,
            'description'       => $description,
            'mrp'               => (string)number_format($this->mrp,2),
            'sale_price'        => (string)number_format($this->sale_price,2),
            'cover_image'       => $cover_image ? $cover_image : '',
            'type'              => $type,
            'expiry_date'       => $end_date ? $end_date : '',
            'available_coupons' => $available_coupons,
            'sold_coupons'      => $sold_coupons,
        ];

    }
}

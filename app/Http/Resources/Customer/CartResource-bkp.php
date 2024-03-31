<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Setting;
use App\Models\OrderItem;
use App\Models\Helpers\CommonHelper;
use DB;
use Auth;

class CartResource extends JsonResource
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

        $book_items = [];
        $coupon_items = [];
        if($this->order_type == 'physical_books')
        {
            $address_required = '1';
            $payment_methods  = $this->get_payment_methods($user->user_type);
            $book_items       = CartItemResource::collection($this->order_items);
        }
        else
        {
            $address_required = '0';
            $payment_methods  = ['payu','ccavenue'];
            $coupon_items     = CouponCartItemResource::collection($this->order_items);
        }

        $points_redeemed = '0';
        if($this->redeemed_points_discount > 0) {
            $points_redeemed = '1';    
        }
        return [
            'cart_id'       => $this->id ? (string)$this->id : '' ,
            'is_payment_attempt'=>$this->is_payment_attempt ? ($this->is_payment_attempt):'',
            'book_items'    => $book_items,
            'coupon_items'  => $coupon_items,
            'user_type'     => $this->user_type,
            'order_summary' => [
                'total_mrp'           => (string)floor($this->total_mrp),
                'discount_on_mrp'     => (string)floor($this->discount_on_mrp),
                'delivery_charges'    => (string)floor($this->delivery_charges),
                'coin_point_discount' => (string)floor($this->redeemed_points_discount),
                'total_payable'       => (string)floor($this->total_payable),
            ],
            'address_required' => (string)$address_required,
            'payment_methods'  => $payment_methods,
            'points_redeemed'  => (string)$points_redeemed,
            'earned_points'    => (string)(integer)$user->points,
            'points_formula'   => Setting::get('points_per_rs')." coins = 1 Rs.",
            'payu_wait_time' => floor(Setting::get('payu_job_delay_in_seconds')/60),
        ];
    }
}

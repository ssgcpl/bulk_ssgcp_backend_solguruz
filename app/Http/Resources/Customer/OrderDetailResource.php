<?php
namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Setting;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;

class OrderDetailResource extends JsonResource
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
    
        $payment_method = ucfirst($this->payment_type);
        if($this->order_status == 'pending'){
            $this->order_status = 'under_process';
        }

        $book_items = [];
        $coupon_items = [];
        
        if($this->order_type == 'physical_books')
        {
            $book_items = OrderItemResource::collection($this->order_items);
        }
        else
        {
            $coupon_items = CouponOrderItemResource::collection($this->order_items);
        }

        return [
            'order_id'         => $this->id ? (string)$this->id : '' ,
            'user_id'          => $this->user_id,
            'user_type'        => $this->user_type,  
           // 'order_date'       => date('d-m-Y', strtotime($this->created_at)),
           // 'order_time'       => date('h:i A', strtotime($this->created_at)),
            'order_date'       => date('d-m-Y', strtotime($this->placed_at)),
            'order_time'       => date('h:i A', strtotime($this->placed_at)),
            'order_total'      => (string)number_format($this->total_payable,2),
            'order_status'     => $this->order_status ? (string)(str_replace('_', ' ', ucfirst($this->order_status)) ) : '',
            'billing_address'  => ($this->billing_address) ? new AddressResource($this->billing_address) : $this->object,
            'shipping_address' => ($this->shipping_address) ? new AddressResource($this->shipping_address) : $this->object,
            'book_items'       => $book_items,
            'coupon_items'     => $coupon_items,
            'payment_method'   => $payment_method,
            'order_summary'    => [
                'total_mrp'           => (string)number_format($this->total_mrp,2),
                'discount_on_mrp'     => (string)number_format($this->discount_on_mrp,2),
                'delivery_charges'    => (string)number_format($this->delivery_charges,2),
                'coin_point_discount' => (string)number_format($this->redeemed_points_discount,2),
                'total_payable'       => (string)number_format($this->total_payable,2),
            ],
            
        ];
    }
}

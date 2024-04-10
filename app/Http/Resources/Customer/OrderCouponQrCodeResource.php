<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCouponQrCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
          return [
            'qr_id'            => $this->id ? (string)$this->id : '' ,
            'qr_code_value'    => $this->coupon_qr_code ? (string)$this->coupon_qr_code->qr_code_value : '' ,
            'qr_image'         => $this->coupon_qr_code ? asset($this->coupon_qr_code->qr_code) : '' ,
            'state'            => $this->coupon_qr_code ? (string)$this->coupon_qr_code->state : '' ,
            'customer_name'    => $this->customer_name ?? '',
            'customer_contact' => $this->customer_contact ?? '',
            'sale_price'       => (string)number_format($this->sale_price,2) ?? '',
            'country_code'     => $this->order_item->order->user ? $this->order_item->order->user->country->country_code : ""
        ];
    }
}

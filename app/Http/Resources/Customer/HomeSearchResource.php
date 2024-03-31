<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class HomeSearchResource extends JsonResource
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
        $language = $this->language;
        if($this->language == 'both')
        {
            $language = 'hindi';
        }
        return [
            'product_id'         => $this->id ? (string)$this->id : '' ,
            'search_type'        => $this->search_type ? (string)$this->search_type :'',
            'name'               => $this->display_name ? (string)$this->display_name :'', 
            'language'           => $language, 
            'sale_price'         => $this->sale_price ? (string)floor($this->sale_price) : '0',
            'mrp'                => $this->mrp ? (string)floor($this->mrp) : '' ,
            'image'              => $this->image ? (string)asset($this->image) : '',
            'quantity'           => $this->quantity ? (string)$this->quantity : '0' ,
            'added_to_cart'      => $this->added_to_cart ? (string)$this->added_to_cart : '0' ,
            'cart_item_id'       => $this->cart_item_id ? (string)$this->cart_item_id :'',
            'stock_status'       => $this->stock_status ? (string)$this->stock_status :'',
            'stock_status_label' => $this->stock_status ? (string)trans('products.'.$this->stock_status) :'',
            'type'               => $this->coupon ? (string)$this->coupon->item_type : '' ,
            'type_label'         => $this->coupon ? (string)trans('coupons.'.$this->coupon->item_type) : '' ,
            'expiry_date'        => $this->coupon ? (string)date('d-m-Y',strtotime($this->coupon->end_date)) : '' ,
           
        ];
    }
}

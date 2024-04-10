<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Helpers\CommonHelper;
use DB;
use Auth;

class CartItemResource extends JsonResource
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
        $item = $this->product;
        $user = Auth::guard('api')->user();
        if($user) {
            $cover_image = $item ? asset($item->image) : '';
            $weight = $item->weight;

            //Select display language
            if($this->lang == 'english' || $this->language == 'english'){
               $name        = $item->name_english ? (string)$item->name_english : '';
               $description = $item->sub_heading_english ? (string)$item->sub_heading_english : '';
            }else{
               $name        = $item->name_hindi ? (string)$item->name_hindi : '';
               $description = $item->sub_heading_hindi ? (string)$item->sub_heading_hindi : '';
            }

            $sale_price = $item->get_price($user);
            
        }

        //last returnable days count
        $now = time();
        $returnable_date = strtotime($item->last_returnable_date);
        $datediff = $returnable_date - $now;
        $days_count = round($datediff / (60 * 60 * 24));
        $last_returnable_days = 0;
        if($days_count > 0)
        {
            $last_returnable_days = $days_count;
        }
        

        return [
            'cart_item_id'         => $this->id ? (string)$this->id : '' ,
            'quantity'             => $this->supplied_quantity ? (string)$this->supplied_quantity : '' ,
            'product_id'           => isset($item) ? (string)$item->id : '' ,
            'name'                 => $name,
            'description'          => $description,
            'weight'               => $weight,
            'mrp'                  => isset($item) ? (string)number_format($item->mrp,2) : '' ,
            'sale_price'           => (string)number_format($sale_price,2),
            'cover_image'          => $cover_image ? $cover_image : '',
            'last_returnable_date' => isset($item) ? (string)date('d-m-Y',strtotime($item->last_returnable_date)) : '' ,
            'last_returnable_days' => (string)$last_returnable_days,
            'returnable_qty'       => isset($item) ? (string)$item->last_returnable_qty : '' ,
        ];
    }
}

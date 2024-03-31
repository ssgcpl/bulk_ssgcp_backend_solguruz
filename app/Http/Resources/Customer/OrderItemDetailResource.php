<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Helpers\CommonHelper;
use DB;
use Auth;
use App\Models\WishList;

class OrderItemDetailResource extends JsonResource
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
        $added_to_return_cart = '0';
        if($user) {
            $return_cart = $this->itemAddedToCartChecker('order_return',$this->id,$user);
            if($return_cart)
            {
                $added_to_return_cart = '1';
            }
        }

        // Book details 
         if( $item->language == 'english'){

            $name                    = $item->name_english ? (string)$item->name_english : '' ;
            $sub_heading                    = $item->sub_heading_english ? (string)$item->sub_heading_english : '' ;
            $description                    = $item->description_english ? (string)$item->description_english : '' ;
            $additional_info                    = $item->additional_info_english ? (string)$item->additional_info_english : '' ;

        }else{

            $name                    = $item->name_hindi ? (string)$item->name_hindi : '' ;
            $sub_heading                    = $item->sub_heading_hindi ? (string)$item->sub_heading_hindi : '' ;
            $description                    = $item->description_hindi ? (string)$item->description_hindi : '' ;
            $additional_info                    = $item->additional_info_hindi ? (string)$item->additional_info_hindi : '' ;
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

        $max_returnable_quantity = $item->get_max_return_quantity($this->supplied_quantity);
        return [
            'order_item_id'           => $this->id ? (string)$this->id : '' ,
            'name'                 => $name,
            'sub_heading'          => $sub_heading,
            'description'          => $description,
            'additional_info'      => $additional_info,
            'sale_price'           => $item->get_price($user) ? (string)floor($item->get_price($user)) : '' ,
            'mrp'                  => $item->mrp ? (string)floor($item->mrp) : '' ,
            'weight'               => $item->weight ? (string)$item->weight : '' ,
            'cover_images'         => $item->cover_images ? BookImageResource::collection($item->cover_images) : array(),
            'quantity'                => $this->supplied_quantity ? (string)$this->supplied_quantity : '' ,
            'product_id'              => isset($item) ? (string)$item->id : '' ,
            'last_returnable_date'    => isset($item) ? (string)date('d-m-Y',strtotime($item->last_returnable_date)) : '' ,
            'last_returnable_days'    => (string)$last_returnable_days,
            'returnable_qty'          => isset($item) ? (string)$item->last_returnable_qty : '' ,
            'max_returnable_quantity' => $max_returnable_quantity ? (string)$max_returnable_quantity : '' ,
            'added_to_return_cart'    => $added_to_return_cart,
            'order_status'            => $this->order->order_status,
        ];

    }
}

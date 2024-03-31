<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Helpers\CommonHelper;
use DB;
use Auth;
use App\Models\WishList;

class OrderItemResource extends JsonResource
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
        $exist_under_dealer = '0';
        $added_to_cart = '0';
        $added_quantity = '0';
        $added_to_wishlist = '0';
        $added_to_return_cart = '0';
        if($user) {
            if(count($user->dealers) > 0)
            {
                $exist_under_dealer = '1';
            }
            $cover_image = $item ? asset($item->image) : '';
            $weight = $item->weight;
            //Select display language
            /*if($this->lang == 'english' || $this->language == 'english'){
               $name        = $item->name_english ? (string)$item->name_english : '';
               $description = $item->sub_heading_english ? (string)$item->sub_heading_english : '';
            }else{
               $name        = $item->name_hindi ? (string)$item->name_hindi : $item->name_english;
               $description = $item->sub_heading_hindi ? (string)$item->sub_heading_hindi : $item->sub_heading_english;
            }*/

            $sale_price = $item->get_price($user);

            $cart = $this->itemAddedToCartChecker('order',$item->id,$user);
            if($cart)
            {
                $added_to_cart = '1';
                $added_quantity = $cart->supplied_quantity;
            }

            $return_cart = $this->itemAddedToCartChecker('order_return',$this->id,$user);
            if($return_cart)
            {
                $added_to_return_cart = '1';
            }

            $wishlist = WishList::where('user_id',$user->id)->where('product_id',$item->id)->first();
            if($wishlist)
            {
                $added_to_wishlist = '1';
            }
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
            'order_item_id'        => $this->id ? (string)$this->id : '' ,
            'quantity'             => $this->supplied_quantity ? (string)$this->supplied_quantity : '' ,
            'product_id'           => isset($item) ? (string)$item->id : '' ,
            'name'                 => $this->book_name,
            'description'          => $this->book_description,
            'weight'               => $weight,
            'language'             => (string)$this->language,
            'mrp'                  => (string)floor($this->mrp),
            'sale_price'           => (string)floor($this->sale_price),
            'cover_image'          => $cover_image ? $cover_image : '',
            'last_returnable_date' => isset($item) ? (string)date('d-m-Y',strtotime($item->last_returnable_date)) : '' ,
            'last_returnable_days' => (string)$last_returnable_days,
            'returnable_qty'       => isset($item) ? (string)$item->last_returnable_qty : '' ,
            'added_to_cart'        => $added_to_cart,
            'added_quantity'       => (string)$added_quantity,
            'added_to_wishlist'    => $added_to_wishlist,
            'stock_status'         => $item->stock_status ? (string)$item->stock_status : '' ,
            'stock_status_label'   => $item->stock_status ? (string)trans('products.'.$item->stock_status) : '',
            'added_to_return_cart' => $added_to_return_cart,
            'exist_under_dealer'   => $exist_under_dealer,
        ];

    }
}

<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Http\Resources\Customer\BookImageResource;
use App\Http\Resources\Customer\RelatedBookResource;
use App\Models\Helpers\CommonHelper;

class BookDetailResource extends JsonResource
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
        //Select display language
        if($this->lang == 'english' || $this->language == 'english'){

            $name                    = $this->name_english ? (string)$this->name_english : '' ;
            $sub_heading                    = $this->sub_heading_english ? (string)$this->sub_heading_english : '' ;
            $description                    = $this->description_english ? (string)$this->description_english : '' ;
            $additional_info                    = $this->additional_info_english ? (string)$this->additional_info_english : '' ;

        }else{

            $name                    = $this->name_hindi ? (string)$this->name_hindi : '' ;
            $sub_heading                    = $this->sub_heading_hindi ? (string)$this->sub_heading_hindi : '' ;
            $description                    = $this->description_hindi ? (string)$this->description_hindi : '' ;
            $additional_info                    = $this->additional_info_hindi ? (string)$this->additional_info_hindi : '' ;
        }

        //last returnable days count
        $now = time();
        $returnable_date = strtotime($this->last_returnable_date);
        $datediff = $returnable_date - $now;
        $days_count = round($datediff / (60 * 60 * 24));
        $last_returnable_days = 0;
        if($days_count > 0)
        {
            $last_returnable_days = $days_count;
        }

        return [
            'book_id'              => $this->id ? (string)$this->id : '' ,
            'name'                 => $name,
            'sub_heading'          => $sub_heading,
            'description'          => $description,
            'additional_info'      => $additional_info,
            'sale_price'           => $this->price ? (string)floor($this->price) : '' ,
            'mrp'                  => $this->mrp ? (string)floor($this->mrp) : '' ,
            'weight'               => $this->weight ? (string)$this->weight : '' ,
            'language'             => $this->language ? (string)$this->language : '' ,
            'stock_status'         => $this->stock_status ? (string)$this->stock_status : '' ,
            'stock_status_label'   => $this->stock_status ? (string)trans('products.'.$this->stock_status) : '' ,
            'image'                => $this->image ? (string)asset($this->image) : '',
            'cover_images'         => $this->cover_images ? BookImageResource::collection($this->cover_images) : array(),
            'added_to_cart'        => $this->added_to_cart ? (string)$this->added_to_cart :'0',
            'cart_item_id'         => $this->cart_item_id ? (string)$this->cart_item_id :'',
            'related_products'     => $this->rel ? BookResource::collection($this->rel) : array(),
            'last_returnable_date' => $this->last_returnable_date ? (string)date('d-m-Y',strtotime($this->last_returnable_date)) : '' ,
            'last_returnable_days' => (string)$last_returnable_days,
            'returnable_qty'       => $this->last_returnable_qty ? (string)$this->last_returnable_qty : '' ,
            'quantity'             => $this->quantity ? (string)$this->quantity : '0'
            
        ];
    }
}

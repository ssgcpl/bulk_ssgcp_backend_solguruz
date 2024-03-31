<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;
use App\Models\Setting;
use App\Http\Resources\Customer\ReturnOrderItemList;


class ReturnOrderDetailsResource extends JsonResource
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
        if($this->order_status == 'return_placed'){
            
            $return_address = Setting::get('order_return_address');
            $receiver_number = Setting::get('order_return_contact_number');

        }else{

            $return_address = '';
            $receiver_number = '';
        }

        return [
            'order_return_id' => $this->id ? (string)$this->id : '',
            'return_at'     => $this->returned_at ? (string)$this->returned_at : null,
            'return_date'     => $this->returned_at ? (string)$this->returned_at->format('d-m-Y') : '',
            'return_time'     => $this->returned_at ? (string)$this->returned_at->format('H:i a') : '',
            'total_return_quantity'    => $this->total_quantity ? (string)$this->total_quantity :'',
            'accepted_quantity'    => $this->accepted_quantity ? (string)$this->accepted_quantity :'',
            'total_sale_price'    => $this->total_sale_price ? (string)floor($this->total_sale_price) :'',
            'status'    => $this->order_status ? (string)$this->order_status :'',
            'status_label'    => $this->order_status ? (string)trans('order_return.'.$this->order_status) :'',
            'return_address' => $return_address,
            'country_code' => $this->user ? (string)$this->user->country->country_code : "",
            'receiver_number' => $receiver_number,
            'return_items' => $this->order_items ? ReturnOrderItemList::collection($this->order_items) : array(),
           
        ];
    }
}

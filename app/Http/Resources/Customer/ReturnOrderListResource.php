<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;
use App\Models\Setting;

class ReturnOrderListResource extends JsonResource
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

        return [
            'order_return_id' => $this->id ? (string)$this->id : '',
            'return_at'     => $this->returned_at ? (string)$this->returned_at : null,
            'return_date'     => $this->returned_at ? (string)$this->returned_at->format('d-m-Y') : '',
            'total_return_quantity'    => $this->total_quantity ? (string)$this->total_quantity :'',
            'total_sale_price'    => $this->total_sale_price ? (string)floor($this->total_sale_price) :'',
            'status'    => $this->order_status ? (string)$this->order_status :'',
            'status_label'    => $this->order_status ? (string)trans('order_return.'.$this->order_status) :'',

        ];
    }
}

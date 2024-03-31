<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Helpers\CommonHelper;
use App\Models\FavoriteBook;
use DB;
use Auth;

class OrderListResource extends JsonResource
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
        if($this->order_status == 'pending'){
            $this->order_status = 'under_process';
        }
        return [
            'id'           => (string)$this->id,
            'order_status' => $this->order_status ? (string)(str_replace('_', ' ', ucfirst($this->order_status)) ) : '',
           // 'order_date'   => date('d-m-Y', strtotime($this->created_at)),
           // 'order_time'   => date('h:i A', strtotime($this->created_at)),
            'order_date'   => date('d-m-Y', strtotime($this->placed_at)),
            'order_time'   => date('h:i A', strtotime($this->placed_at)),
            'order_total'  => (string)floor($this->total_payable),
        ];
    }
}

<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class PreviousOrderResource extends JsonResource
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
       /* return [
            'order_return_id' => $this->order_return_id ? (string)$this->order_return_id : '',
            'book_id'     => $this->product_id ? (string)$this->product_id : '',
            'name'        => $this->product ? (string)$this->product->get_name() :'', 
            'sale_price'  => $this->sale_price ? (string)floor($this->sale_price) : '',
            'mrp'         => $this->product ? (string)floor($this->product->mrp) : '',
            'quantity'    => $this->total_quantity ? (string)$this->total_quantity :'',
            'image'       => $this->product ? $this->product->image ? (string)asset($this->product->image) : '' :'',
            'status'    => $this->order_return ? $this->order_return->order_status ? (string)$this->order_return->order_status :'' :'',
            'status_label'    => $this->order_return ? $this->order_return->order_status ? (string)trans('order_return.'.$this->order_return->order_status) :'' :'',
            'total_sale_price' => $this->total_sale_price ? (string)floor($this->total_sale_price) : '',
            'return_date' => $this->order_return ? $this->order_return->returned_at ? (string)$this->order_return->returned_at->format('d-m-Y') :'':'',
        ];*/
    }
}

<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class BusinessCategoryResource extends JsonResource
{   
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        'id' => $this->id ? (string)$this->id : '' ,
        'name' => $this->category_name ? (string)$this->category_name : '' ,
        'type' => $this->layout ? $this->layout :'books',
        'url' => $this->url ? $this->url : '',
        'image' => $this->category_image ? asset($this->category_image) : asset('default.png'),
      ];
    }
}

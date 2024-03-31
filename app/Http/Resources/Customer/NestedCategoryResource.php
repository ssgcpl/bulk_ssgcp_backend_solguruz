<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class NestedCategoryResource extends JsonResource
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
        'category_name' => $this->category_name ? (string)$this->category_name : '' ,
      ];
    }
}

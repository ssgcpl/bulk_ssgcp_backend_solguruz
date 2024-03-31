<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
          return [
            'id'    => $this->id ? (string)$this->id : '' ,
            'image' => $this->images ? (string)asset($this->images) : '' 
        ];
    }
}

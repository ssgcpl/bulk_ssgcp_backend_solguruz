<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class PostcodeResource extends JsonResource
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
            'id'        => $this->id ? (string)$this->id : '' ,
            'city_id'   => $this->city_id ? (string)$this->city_id : '' ,
            'city_name' => $this->city->name ? (string)$this->city->name : '' ,
            'postcode'  => (string)ucfirst($this->postcode),
        ];
    }
}

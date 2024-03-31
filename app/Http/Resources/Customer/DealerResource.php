<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class DealerResource extends JsonResource
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
            'id'            => $this->id ? (string)$this->id : '' ,
            'name'          => (string)$this->first_name ?? "",
            'profile_image' => $this->profile_image ? asset($this->profile_image) : asset('customer_avtar.jpg'),
        ];
    }
}

<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // echo "<pre>";print_r($this->post_code->city);exit;
        // return parent::toArray($request);
        $postcode = "";
        if(isset($this->postcode))
        {
            $postcode = $this->postcode;
        }
        else
        {
            $postcode = $this->postal_code;
        }
        return [
            'id'                  => (string)$this->id,
            'contact_name'        => (string)$this->contact_name ?? "",
            'company_name'        => (string)$this->company_name ?? "",
            'contact_number'      => (string)$this->contact_number ?? "",
            'country_code'        => $this->user ? (string)$this->user->country->country_code : "",
            'email'               => (string)$this->email ?? "",
            'state_id'            => ($this->post_code) ? ($this->post_code->city ? (string)$this->post_code->city->state_id : "") : '',
            'state_name'          => (string)$this->state ?? "",
            'city_id'             => ($this->post_code) ? (string)$this->post_code->city_id : "",
            'city_name'           => (string)$this->city ?? "",
            'postcode_id'         => (string)$this->postcode_id ?? "",
            'postcode'            => (string)$postcode,
            'area'                => (string)$this->area ?? "",
            'house_no'            => (string)$this->house_no ?? "",
            'street'              => (string)$this->street ?? "",
            'landmark'            => (string)$this->landmark ?? "",
            'address_type'        => (string)$this->address_type ?? "",
            'is_delivery_address' => (string)$this->is_delivery_address ?? "",
        ];
    }
}

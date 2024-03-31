<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Guest;
use App\Models\Setting;
use App\Models\Country;
use App\Models\State;
use DB;

class UserResource extends JsonResource
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
        'token'             => $this->when(isset($this->accessToken), $this->accessToken),
        'id'                => $this->id ? (string)$this->id : '' ,
        'first_name'        => (string)$this->first_name ?? "",
        'last_name'         => (string)$this->last_name ?? "",
        'email'             => (string)$this->email ?? "",
        'mobile_number'     => (string)$this->mobile_number ?? "",
        'profile_image'     => $this->profile_image ? asset($this->profile_image) : asset('customer_avtar.jpg'),
        'country'           => new CountryResource($this->country),
        'referral_code'     => $this->referrer ? (string)$this->referrer->referral_code : '',
        'email_verified'    => $this->email_verified_at != NULL ? "1" : "0",
        'phone_verified'    => $this->verified ? "1" : "0",
        'is_social_login'   => $this->social_type ? (string)$this->social_type : '',
        'company_name'      => (string)$this->company_name ?? "",
        'company_documents' => CompanyDocumentResource::collection($this->company_documents),
        'company_images'    => CompanyImageResource::collection($this->company_images),
        'user_type'         => $this->user_type
      ];
    }
}

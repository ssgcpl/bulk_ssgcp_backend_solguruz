<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\Helpers\CommonHelper;


class CouponCoverImageResource extends JsonResource
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
            'id'    => $this->id ? (string)$this->id : '' ,
            'image' => $this->cover_image ? (string)asset($this->cover_image) : ''
        ];
    }
}

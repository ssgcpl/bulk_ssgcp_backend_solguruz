<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
use Auth;
use App\Models\NewsBookmark;

class BookImageResource extends JsonResource
{   
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $is_favorite = "0";
        
        return [
            'id'    => $this->id ? (string)$this->id : '' ,
            'image' => $this->image ? (string)asset($this->image) : '' ,
        ];
    }
}

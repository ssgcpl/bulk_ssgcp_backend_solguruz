<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

use Carbon\Carbon;

class NotificationResource extends JsonResource
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

        'id'         => $this->id ? (string)$this->id : '' ,
        'user_id'    => $this->user_id ? (string)$this->user_id : '',
        'title'      => $this->title ? (string)$this->title : '',
        'content'    => $this->content ? (string)$this->content : '',
        'slug'       => $this->slug ? (string)$this->slug : '',
        'url'        => $this->url ? (string)$this->url : '',
        'data_id'    => $this->data_id ? (string)$this->data_id : '',
        'created_at' => $this->created_at ? (string)$this->created_at : '',
        'is_read'    => $this->is_read ? (string)$this->is_read : '0',
        'date'       => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
      ];
    }
}

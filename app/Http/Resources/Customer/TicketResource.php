<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Reason;
use DB;

class TicketResource extends JsonResource
{   
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     static
     */
    public function toArray($request)
    {
      $admin_action_date = $admin_action_time = '';

      if($this->status == 'pending') {
        $status = trans('tickets.submitted');
      }

      if($this->status == 'acknowledged') {
        $admin_action_date = date('d-m-Y', strtotime($this->acknowledged_date));
        $admin_action_time = date('h:i a', strtotime($this->acknowledged_date));
        $status            = trans('tickets.under_review');
      }

      if($this->status == 'resolved') {
        $admin_action_date = date('d-m-Y', strtotime($this->resolved_date));
        $admin_action_time = date('h:i a', strtotime($this->resolved_date));
        $status            = trans('tickets.resolved');
      }

      $reason = Reason::find($this->reason_id);
      return [
          'ticket_number'   => $this->id ? (string)$this->id : '' ,
          'date'            => date('d-m-Y',strtotime($this->created_at)),
          'time'            => date('H:i A',strtotime($this->created_at)),
          'reason'          => @($reason) ? new ReasonResource($reason) : '',
          'message'         => $this->message ? $this->message : '',
          'admin_comment'   => $this->comment ? $this->comment : '',
          'acknowledged_comment'   => $this->acknowledged_comment ? $this->acknowledged_comment : '',
          'status'          => $status,
          'status_original' => $this->status,
          'admin_action_date' => $admin_action_date,
          'admin_action_time' => $admin_action_time,
      ];
  }
}

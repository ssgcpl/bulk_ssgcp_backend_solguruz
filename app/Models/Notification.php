<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $fillable = ['user_id','user_type','title','content','is_sent','is_read','send_by','slug','data_id'];


    // change description name 
    public function getDescriptionForEvent(string $eventName): string
    {
        return trans('activity_logs.log', ['module' => 'Notification', 'type' => $eventName]);
    }

    // log name change from here 
    protected static $logName = 'Notification';

    // only changes will comes 
    protected static $logOnlyDirty = true;

    // show all attributes 
    protected static $logAttributes = ['*'];

    //  ignore 
    // protected static $ignoreChangedAttributes = ['password','updated_at'];

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];


    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
   
}

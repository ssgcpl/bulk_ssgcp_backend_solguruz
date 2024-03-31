<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceDetail extends Model
{	
	
    protected $table = 'device_details';

    protected $fillable = [
  		'user_id','device_token','device_type','uuid','ip','os_version','model_name'
    ];

    // change description name 
    public function getDescriptionForEvent(string $eventName): string
    {
                return trans('activity_logs.log', ['module' => 'Device', 'type' => $eventName]);
    }

    // log name change from here 
    protected static $logName = 'Device';

    // only changes will comes 
    protected static $logOnlyDirty = true;

    // show all attributes 
    protected static $logAttributes = ['*'];

    //  ignore 
    // protected static $ignoreChangedAttributes = ['password','updated_at'];

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];


    public function user()
    {
        return $this->belongsTo('App\Models\User','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\State;
// use Spatie\Activitylog\Traits\LogsActivity;

class PostCode extends Model
{
	// use LogsActivity;

	protected $table = 'postcodes';
    protected $fillable = ['city_id','postcode','status'];

    /**
     * @return mixed
     */

    // change description name
    /*public function getDescriptionForEvent(string $eventName): string
    {
        return trans('activity_logs.log', ['module' => 'Post Code', 'type' => $eventName]);
    }

    // log name change from here
    protected static $logName = 'Post Code';

    // only changes will comes
    protected static $logOnlyDirty = true;

    // show all attributes
    protected static $logAttributes = ['*'];*/

   public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

}
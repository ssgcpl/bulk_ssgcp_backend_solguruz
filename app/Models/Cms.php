<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{	
	
	protected $guarded = [];


    // log name change from here 
    protected static $logName = 'Cms';

    // only changes will comes 
    protected static $logOnlyDirty = true;

    // show all attributes 
    protected static $logAttributes = ['*'];  
}
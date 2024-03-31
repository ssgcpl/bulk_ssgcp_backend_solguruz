<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'email_verified_at',
        'mobile_number',
        'password',
        'user_type',
        'profile_image',
        'referral_code',
        'user_discount',
        'social_id',
        'social_type',
        'registered_on',
        'preferred_language',
        'lattitude',
        'longitude',
        'status',
        'verified',
        'country_id',
        'is_pwd_converted',
        'referrer_id',
        'points'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // change description name 
    public function getDescriptionForEvent(string $eventName): string
    {
         return trans('activity_logs.log', ['module' => 'User', 'type' => $eventName]);
    }

    // log name change from here 
    protected static $logName = 'User';

    // only changes will comes 
    protected static $logOnlyDirty = true;

    // show all attributes 
    protected static $logAttributes = ['*'];

    //  ignore 
    // protected static $ignoreChangedAttributes = ['password','updated_at'];

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
      

    // vendor details
    public function profile()
    {
      return $this->hasOne('App\Models\Profile','user_id','id');
    }

    // Country
    public function country()
    {
      return $this->hasOne('App\Models\Country','id','country_id');
    }

    // State
    public function state()
    {
      return $this->hasOne('App\Models\State','id','state_id');
    }

    // City
    public function city()
    {
      return $this->hasOne('App\Models\City','id','city_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    // Company Images
    public function company_images()
    {
        return $this->hasMany('App\Models\CompanyDocImage','company_id','id')->whereNotNull('images');
    }

    //Company Documents
    public function company_documents()
    {
        return $this->hasMany('App\Models\CompanyDocImage','company_id','id' )->whereNotNull('documents');
    }

    //addresses
    public function addresses(){
        return $this->hasMany('App\Models\Address','user_id');
    }

    //addresses
    public function address(){
        return $this->hasOne('App\Models\Address','user_id');
    }

    //addresses
    public function delivery_address(){
        return $this->hasOne('App\Models\Address','user_id')->where('is_delivery_address','yes');
    }

    //dealers
    public function dealers(){
        return $this->hasMany('App\Models\DealerRetailer','retailer_id','id');
    }

    //Device Details
    public function device_details()
    {
      return $this->hasMany('App\Models\DeviceDetail','user_id','id');
    }


    


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';

    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'contact_name',
        'company_name',
        'contact_number',
        'email',
        'state',
        'city',
        'postcode',
        'postcode_id',
        'area',
        'house_no',
        'street',
        'landmark',
        'address_type',
        'is_delivery_address',
        'latitude',
        'longitude',
    ];


    public function post_code()
    {
        return $this->belongsTo(PostCode::class,'postcode_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
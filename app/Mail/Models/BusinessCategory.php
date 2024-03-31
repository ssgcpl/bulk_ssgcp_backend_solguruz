<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    use HasFactory;
    protected $table = 'business_categories';
	
	protected $guarded = [];

	public function parent(){
    	return $this->hasOne(Category::class, 'id','parent_id');
  	}

}

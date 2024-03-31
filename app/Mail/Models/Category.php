<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
 		'category_name','parent_id', 'level','is_live','added_by','status'
 	];

 	 // Get Parent Category
    public function parent(){
        return $this->belongsTo('App\Models\Category','parent_id');
    }

    public function childs(){
        return Category::where('parent_id', $this->id)->get();
    }

    public function childs_active(){
        return Category::where('parent_id', $this->id)->where('is_live','1')->where('status','active')->get();
    }

    public function sub_category(){
        return $this->hasMany('App\Models\Category','parent_id','id')->where('status','active')->where('is_live','1') ;
    }
}

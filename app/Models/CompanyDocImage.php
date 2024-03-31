<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocImage extends Model
{
    use HasFactory;
     protected $table = 'company_docs_images';

    protected $fillable = [
  		'company_id','documents','images'
    ];
}

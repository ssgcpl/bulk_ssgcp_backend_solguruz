<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

  protected $table = "products";
	protected $fillable = [
 		'name_english','sub_heading_english','description_english','additional_info_english','name_hindi','sub_heading_hindi','description_hindi','additional_info_hindi','language','business_category_id','last_returnable_date','last_returnable_qty','visible_to','image','mrp','dealer_sale_price','retailer_sale_price','sku_id','weight','length','height','width','stock_status','status','is_live','added_by','published_at','republished_at'
 	];


 	//cover images
    public function cover_images(){
           return $this->hasMany('App\Models\ProductCoverImage','product_id');
    }

    //categories
    public function categories(){
           return $this->hasMany('App\Models\ProductCategory','product_id');
    }

    //related products
    public function related_products(){
           return $this->hasMany('App\Models\RelatedProduct','product_id');
    }

    //Related Products Data
    public function related_products_data(){
      return $this->hasManyThrough(Product::class, RelatedProduct::class, 'product_id','id','id','related_product_id');
    }

    public function get_price($user){

      //check for user type and user specific discount given by admin for each user
      $user_discount = 0;
      if($user->user_type == 'retailer'){
        if($user->user_discount != NULL)
        {
          $user_discount = ($this->retailer_sale_price * $user->user_discount) / 100;
        }
        $retailer_sale_price = $this->retailer_sale_price - $user_discount;
        return round($retailer_sale_price);
      }else{
        if($user->user_discount != NULL)
        {
          $user_discount = ($this->dealer_sale_price * $user->user_discount) / 100;
        }
        $dealer_sale_price = $this->dealer_sale_price - $user_discount;
        return round($dealer_sale_price);
      }
    }

    public function get_name(){

      if($this->language == 'english'){
        return $this->name_english;
      }else{
        return $this->name_hindi;
      }
    }

    public function get_sub_heading(){

      if($this->language == 'english'){
        return $this->sub_heading_english;
      }else{
        return $this->sub_heading_hindi;
      }
    }

    public function get_description(){

      if($this->language == 'english'){
        return $this->description_english;
      }else{
        return $this->description_hindi;
      }
    }

    public function get_additional_info(){

      if($this->language == 'english'){
        return $this->additional_info_english;
      }else{
        return $this->additional_info_hindi;
      }
    }


    public function get_max_return_quantity($quantity){

      $max = ($quantity * $this->last_returnable_qty) / 100;
      return floor($max);
    }

    //Wishlist
    public function wishlist(){
      return $this->hasMany('App\Models\Wishlist','product_id','id');
    }

    //Order Items
    public function order_items(){
      return $this->hasMany('App\Models\OrderItem','product_id','id');
    }

    //Latest Barcode
    public function latest_barcode(){
      return $this->hasOne('App\Models\ProductBarcode','product_id','id')->orderBy('created_at','DESC');
    }


    
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePriceFieldsOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_mrp',20,2)->default(0)->change(); 
            $table->decimal('total_sale_price',20,2)->default(0)->change(); 
            $table->decimal('discount_on_mrp',20,2)->default(0)->change(); 
            $table->decimal('coupon_discount',20,2)->default(0)->change();
            $table->decimal('delivery_charges',20,2)->default(0)->change(); 
            $table->decimal('total_payable',20,2)->default(0)->change();  
            $table->decimal('redeemed_points_discount',20,2)->default(0)->change();  
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('mrp',20,2)->default(0)->change();
            $table->decimal('sale_price',20,2)->default(0)->change(); 
            $table->decimal('coupon_discount',20,2)->default(0)->change();
            $table->decimal('total_mrp',20,2)->default(0)->change();
            $table->decimal('total_sale_price',20,2)->default(0)->change();
            $table->decimal('total',20,2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

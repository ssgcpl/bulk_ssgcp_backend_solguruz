<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePriceFieldsAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('mrp',20,2)->nullable()->default(0)->change();
            $table->decimal('dealer_sale_price',20,2)->nullable()->default(0)->change();
            $table->decimal('retailer_sale_price',20,2)->nullable()->default(0)->change();  
        });

        Schema::table('order_returns', function (Blueprint $table) {
            $table->decimal('total_sale_price',20,2)->default(0)->change();
            $table->decimal('accepted_sale_price',20,2)->default(0)->nullable()->change(); 
        });

        Schema::table('order_return_items', function (Blueprint $table) {
            $table->decimal('sale_price',20,2)->default(0)->change();
            $table->decimal('total_sale_price',20,2)->default(0)->change();
            $table->decimal('return_sale_price',20,2)->default(0)->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('refund_amount',20,2)->default(0)->change();
        });

        Schema::table('sub_coupons', function (Blueprint $table) {
            $table->decimal('mrp',20,2)->default(0)->change();
            $table->decimal('sale_price',20,2)->default(0)->change();
        });

        Schema::table('order_coupon_qr_codes', function (Blueprint $table) {
            $table->decimal('sale_price',20,2)->default(0)->change();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_coupons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coupon_master_id')->unsigned();
            $table->foreign('coupon_master_id')->references('id')->on('coupon_master')->onDelete('cascade');
            $table->bigInteger('coupon_id');// ssgc coupon id
            $table->decimal('mrp',10,2)->nullable()->default(0);
            $table->decimal('sale_price',10,2)->nullable()->default(0);
            $table->longtext('description');
            $table->integer('available_quantity');
            $table->string('state')->nullable(); //fresh, redeemed
            $table->string('image');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('sub_coupon_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sub_coupon_id')->unsigned();
            $table->foreign('sub_coupon_id')->references('id')->on('sub_coupons')->onDelete('cascade');
            $table->text('cover_image');
            $table->timestamps();
        });


        Schema::create('sub_coupon_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sub_coupon_id')->unsigned();
            $table->foreign('sub_coupon_id')->references('id')->on('sub_coupons')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_coupons');
        Schema::dropIfExists('sub_coupon_images');
        Schema::dropIfExists('sub_coupon_category');
    }
}

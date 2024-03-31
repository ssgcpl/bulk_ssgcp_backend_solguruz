<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCouponQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_coupon_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_item_id')->unsigned()->nullable();
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->bigInteger('coupon_qr_code_id')->unsigned()->nullable();
            $table->foreign('coupon_qr_code_id')->references('id')->on('coupon_qr_codes')->onDelete('cascade');
            $table->string('status')->default('available')->comment("available,sold");
            $table->string('customer_name')->nullable();
            $table->string('customer_contact')->nullable();
            $table->decimal('sale_price',10,2)->default(0); 
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
        Schema::dropIfExists('order_coupon_qr_codes');
    }
}

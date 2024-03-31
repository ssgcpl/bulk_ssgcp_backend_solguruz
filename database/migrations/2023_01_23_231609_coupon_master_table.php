<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_master', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coupon_id');// ssgc coupon id
            $table->string('type')->nullable(); //Online
            $table->string('name')->nullable();
            $table->string('item_type')-> nullable(); //for online coupons: e_books, videos, test_series, packages
            $table->string('item_name');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->string('usage_limit')->nullable();
            $table->string('quantity')->nullable();
            $table->string('discount');
            $table->longtext('description');
            $table->string('is_live')->default('0');
            $table->string('state')->nullable(); //fresh, redeemed
            $table->string('status')->nullable();
            $table->string('is_deleted')->default('0');
            $table->timestamps();
        });

        Schema::create('coupon_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coupon_master_id')->unsigned()->nullable();
            $table->string('qr_code_value')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('state')->nullable();
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
        Schema::dropIfExists('coupon_master');
        Schema::dropIfExists('coupon_qr_codes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('total_mrp',10,2)->default(0); 
            $table->decimal('total_sale_price',10,2)->default(0); 
            $table->decimal('discount_on_mrp',10,2)->default(0); 
            $table->decimal('coupon_discount',10,2)->default(0);
            $table->decimal('delivery_charges',10,2)->default(0); 
            $table->decimal('total_payable',10,2)->default(0); 
            $table->string('order_type')->nullable()->comment('physical,digital'); // physical_books,digital_coupons
            $table->string('order_status')->default('pending')->comment(' on_hold,processing,shipped,cancelled etc'); // pending_payment, on_hold,processing,shipped,refunded,failed,cancelled etc
            $table->string('print_status')->default('remaining')->nullable(); // remaining, printed
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('payment_type')->nullable(); // online,offline
            $table->integer('bundles')->nullable();
            $table->enum('is_cart', ['0','1'])->default('0');
            $table->integer('cancel_reason_id')->nullable();
            $table->text('cancel_comment')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->decimal('mrp',10,2)->default(0); // 600
            $table->decimal('sale_price',10,2)->default(0); // 500
            $table->bigInteger('coupon_id')->unsigned()->nullable(); 
            //add coupon id foreign key here
            $table->decimal('coupon_discount',10,2)->default(0); // 100 on sale price
            $table->integer('ordered_quantity')->nullable(); // 2 (per 600)
            $table->integer('supplied_quantity')->nullable(); // 2 (per 600)
            $table->timestamps();
        });

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('city_id')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('landmark')->nullable();
            $table->string('state')->nullable();
            $table->integer('postal_code')->nullable();
            $table->enum('address_type',['Home','Office','Other'])->default('Home');
            $table->enum('is_shipping',['0','1'])->default('0');
            $table->enum('is_billing',['0','1'])->default('0');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });


        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('customer_note')->nullable();
            $table->string('admin_note')->nullable();
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_addresses');
        Schema::dropIfExists('order_notes');
        Schema::dropIfExists('orders');
    }
}

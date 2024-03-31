<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('requested_as')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->integer('accepted_quantity')->nullable();
            $table->integer('rejected_quantity')->nullable();
            $table->decimal('total_sale_price',10,2)->default(0);
            $table->decimal('accepted_sale_price',10,2)->default(0)->nullable();
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('receipt_image')->nullable(); 
            $table->string('order_status')->default('return_placed')->comment(' return_placed,dispatched,in_review,rejected,accepted');
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('payment_type')->nullable(); // online,offline
            $table->enum('is_cart', ['0','1'])->default('0');
            $table->datetime('returned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_return_id')->unsigned()->nullable();
            $table->foreign('order_return_id')->references('id')->on('order_returns')->onDelete('cascade');
            $table->bigInteger('order_item_id')->unsigned()->nullable();
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('requested_as')->nullable();
            $table->decimal('sale_price',10,2)->default(0);
            $table->decimal('total_sale_price',10,2)->default(0);
            $table->decimal('return_sale_price',10,2)->default(0)->nullable();
            $table->integer('total_quantity')->nullable();
            $table->integer('accepted_quantity')->nullable();
            $table->integer('rejected_quantity')->nullable();
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
        Schema::dropIfExists('order_return_items');
        Schema::dropIfExists('order_returns');
    }
}

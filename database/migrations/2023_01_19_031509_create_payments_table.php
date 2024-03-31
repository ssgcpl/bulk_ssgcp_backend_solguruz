<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('payment_for')->nullable(); // subscription or order
            $table->bigInteger('order_id')->nullable(); // from orders table
            $table->float('amount',8,2);
            $table->string('payment_type')->nullable(); // cod, ccavenue or payu
            $table->string('tran_ref')->nullable();
            $table->text('api_response')->nullable();
            $table->enum('status',['pending','paid','cancelled','failed'])->default('pending');
            //Refunds
            $table->decimal('refund_amount',10,2)->nullable();
            $table->string('refund_tran_ref')->nullable();
            $table->text('refund_api_response')->nullable();
            $table->string('refund_status')->nullable()->comment("pending, initiated, refunded"); //pending, initiated, refunded
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
        Schema::dropIfExists('payments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_return_id')->unsigned()->nullable();
            $table->foreign('order_return_id')->references('id')->on('order_returns')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->nullable();
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
        Schema::dropIfExists('order_return_notes');
    }
}

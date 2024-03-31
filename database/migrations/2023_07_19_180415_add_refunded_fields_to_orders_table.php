<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundedFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('is_refunded', ['0','1'])->default('0')->after('order_status');
            $table->integer('refunded_points')->after('is_refunded')->nullable(); //2000 -- 10pts = 1rs
            $table->decimal('refunded_points_amount',10,2)->after('refunded_points')->default(0);//200
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}

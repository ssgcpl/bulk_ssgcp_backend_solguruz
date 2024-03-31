<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_returns', function (Blueprint $table) {
            $table->decimal('total_mrp',10,2)->default(0)->after('total_weight');
        });
        Schema::table('order_return_items', function (Blueprint $table) {
            $table->decimal('mrp',10,2)->default(0)->after('requested_as');
            $table->decimal('total_mrp',10,2)->default(0)->after('mrp');
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

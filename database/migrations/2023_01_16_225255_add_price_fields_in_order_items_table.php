<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceFieldsInOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('total_mrp',10,2)->default(0)->after('sale_price'); // 1200
            $table->decimal('total_sale_price',10,2)->default(0)->after('total_mrp'); // 1000
            $table->decimal('total',10,2)->default(0)->after('total_sale_price'); // 1000
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('total_mrp');
            $table->dropColumn('total_sale_price');
            $table->dropColumn('total');
        });
    }
}

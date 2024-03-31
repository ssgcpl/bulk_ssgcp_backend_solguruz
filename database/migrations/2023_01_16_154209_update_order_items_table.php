<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('order_items', function (Blueprint $table) {
            $table->string('language')->after('supplied_quantity')->nullable();
            $table->integer('is_returned')->after('language')->nullable()->default('0');
        });
        Schema::table('order_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE `order_items` ADD COLUMN `category_id`  BIGINT(20) UNSIGNED NOT NULL AFTER `is_returned`");
        });
         Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

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
            $table->dropColumn('language');
            $table->dropColumn('is_returned');
        });
    }
}

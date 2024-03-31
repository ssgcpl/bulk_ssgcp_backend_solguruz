<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeFieldToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
             $table->renameColumn('type','business_category_id');
        });
        Schema::table('products', function (Blueprint $table) {
            DB::statement("ALTER TABLE `products` CHANGE COLUMN `business_category_id` `business_category_id` BIGINT(20) UNSIGNED NOT NULL");
        });

        Schema::table('products', function (Blueprint $table) {
           $table->foreign('business_category_id')->references('id')->on('business_categories')->onDelete('cascade');
        });


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
           //   $table->renameColumn( 'business_category_id','type')->unsigned();

        });
    }
}

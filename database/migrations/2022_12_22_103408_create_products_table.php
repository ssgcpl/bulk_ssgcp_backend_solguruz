<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_english')->nullable();
            $table->string('sub_heading_english')->nullable();
            $table->longtext('description_english')->nullable();
            $table->longtext('additional_info_english')->nullable();
            $table->string('name_hindi')->nullable();
            $table->string('sub_heading_hindi')->nullable();
            $table->longtext('description_hindi')->nullable();
            $table->longtext('additional_info_hindi')->nullable();
            $table->enum('language',['hindi','english','both'])->nullable();
            $table->integer('type')->nullable();
            $table->datetime('last_returnable_date')->nullable();
            $table->integer('last_returnable_qty')->nullable();
            $table->string('visible_to')->nullable();
            $table->string('image')->nullable();
            $table->decimal('mrp',10,2)->nullable()->default(0);
            $table->decimal('dealer_sale_price',10,2)->nullable()->default(0);
            $table->decimal('retailer_sale_price',10,2)->nullable()->default(0);
            $table->string('sku_id')->nullable();
            $table->string('weight')->nullable();
            $table->string('length')->nullable();
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('stock_status')->nullable();
            $table->string('status',255)->default('active');
            $table->string('is_live',255)->default('0');
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->datetime('published_at')->nullable();
            $table->datetime('republished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('product_cover_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->timestamps();
        });

         Schema::create('product_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });

         Schema::create('related_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('related_product_id')->unsigned();
            $table->foreign('related_product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('product_cover_images');
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('related_products');
        Schema::dropIfExists('products');
       
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->bigInteger('gto_in_no')->nullable();
            $table->integer('gto_in_qty')->nullable()->default(0);
            $table->bigInteger('gto_out_no')->nullable();
            $table->integer('gto_out_qty')->nullable()->default(0);
            $table->bigInteger('scrap_no')->nullable();
            $table->integer('scrap_qty')->nullable()->default(0);
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
        Schema::dropIfExists('stocks_transfer');
    }
}

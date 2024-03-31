<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->bigInteger('pof_no')->nullable();
            $table->integer('pof_qty')->nullable()->default(0);
            $table->bigInteger('ecm_no')->nullable();
            $table->integer('ecm_qty')->nullable()->default(0);
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

         Schema::create('stock_gro', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stock_id')->unsigned()->nullable();
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('set null');
            $table->bigInteger('gro_no')->nullable();
            $table->integer('gro_qty')->nullable()->default(0);
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
        Schema::dropIfExists('stocks_gro');
        Schema::dropIfExists('stocks');
    }
}

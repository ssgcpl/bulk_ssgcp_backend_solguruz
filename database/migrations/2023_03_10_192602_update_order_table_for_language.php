<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderTableForLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('book_name')->nullable()->after('product_id');
            $table->string('book_sub_heading')->nullable()->after('book_name');
            $table->longtext('book_description')->nullable()->after('book_sub_heading');
            $table->longtext('book_additional_info')->nullable()->after('book_description');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductTableColumnsLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->longText('name_english')->change();
            $table->longText('sub_heading_english')->change();
            $table->longText('name_hindi')->change();
            $table->longText('sub_heading_hindi')->change();
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
            $table->string('name_english')->change();
            $table->string('sub_heading_english')->change();
            $table->string('name_hindi')->change();
            $table->string('sub_heading_hindi')->change();
        });
    }
}

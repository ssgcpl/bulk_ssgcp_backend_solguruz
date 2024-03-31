<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name',255);
            $table->text('category_image')->nullable();
            $table->longtext('description')->nullable();
            $table->BigInteger('parent_id')->nullable();
            $table->string('layout',255)->default('');
            $table->string('url')->nullable();
            $table->string('is_live',255)->default('0');
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->integer('display_order')->default(0)->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->string('status',255)->default('active');
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
        Schema::dropIfExists('business_categories');
    }
}

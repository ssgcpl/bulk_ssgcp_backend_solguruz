<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wish_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->string('book_name')->nullable();
            $table->longtext('description')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

         Schema::create('wish_suggestion_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wish_suggestion_id')->unsigned();
            $table->foreign('wish_suggestion_id')->references('id')->on('wish_suggestions')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
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
        Schema::dropIfExists('wish_suggestion_images');
        Schema::dropIfExists('wish_suggestions');
    }
}

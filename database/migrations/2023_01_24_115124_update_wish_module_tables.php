<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWishModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wish_list', function (Blueprint $table) {
            $table->string('user_type')->nullable()->after('user_id');
            $table->dropforeign(['dealer_id']);
            $table->dropColumn('dealer_id');
        });

        Schema::table('wish_return', function (Blueprint $table) {
            $table->string('user_type')->nullable()->after('user_id');
        });

        Schema::create('wish_list_dealers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wish_list_id')->unsigned()->nullable();
            $table->foreign('wish_list_id')->references('id')->on('wish_list')->onDelete('cascade');
            $table->bigInteger('dealer_id')->unsigned()->nullable();
            $table->foreign('dealer_id')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::table('wish_list', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->bigInteger('dealer_id')->unsigned()->nullable()->after('user_id');
            $table->foreign('dealer_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('wish_return', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });

        Schema::dropIfExists('wish_list_dealers');
    }
}

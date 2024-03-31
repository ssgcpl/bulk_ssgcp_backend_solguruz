<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWishlistFieldsInReferralHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_history', function (Blueprint $table) {
            $table->bigInteger('wishlist_id')->unsigned()->nullable()->after('order_id');
            $table->foreign('wishlist_id')->references('id')->on('wish_list')->onDelete('cascade');
            $table->bigInteger('wish_return_id')->unsigned()->nullable()->after('wishlist_id');
            $table->foreign('wish_return_id')->references('id')->on('wish_return')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_history', function (Blueprint $table) {
            //
        });
    }
}

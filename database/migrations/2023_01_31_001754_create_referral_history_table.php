<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_history', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('referrer_id')->unsigned()->nullable();
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('points',8,2)->default(0);
            $table->enum('point_status',['added','deducted']);
            $table->enum('status',['active','inactive'])->default('active');
            $table->enum('refunded',['1','0'])->default('0');
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
        Schema::dropIfExists('referral_history');
    }
}

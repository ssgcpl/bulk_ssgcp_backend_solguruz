<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferEarnFieldsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('referrer_id')->unsigned()->nullable()->after('referral_code')->comment('id of the x user who referred me');
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
            $table->decimal('points',8,2)->default(0)->after('referrer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
            $table->dropColumn('referrer_id');
            $table->dropColumn('points');
        });
    }
}

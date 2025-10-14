<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdAndQuantityToVerifiedBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verified_barcodes', function (Blueprint $table) {
            // Add `user_id` after `status` and set foreign key reference
            $table->unsignedBigInteger('user_id')->after('status')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verified_barcodes', function (Blueprint $table) {
            // Rollback changes
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
        });
    }
}

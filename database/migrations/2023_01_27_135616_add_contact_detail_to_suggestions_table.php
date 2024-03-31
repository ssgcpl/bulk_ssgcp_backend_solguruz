<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactDetailToSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wish_suggestions', function (Blueprint $table) {
            $table->string('mobile_number')->nullable()->after('book_name');
            $table->string('email')->nullable()->after('mobile_number');
        });

        Schema::table('ssgc_suggestions', function (Blueprint $table) {
            $table->string('mobile_number')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('mobile_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wish_suggestions', function (Blueprint $table) {
            //
        });
    }
}

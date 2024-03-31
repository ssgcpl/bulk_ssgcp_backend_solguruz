<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('contact_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->bigInteger('postcode_id')->unsigned()->nullable();
            $table->foreign('postcode_id')->references('id')->on('postcodes')->onDelete('cascade');
            $table->string('area')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('landmark')->nullable();
            $table->enum('address_type',['Home','Office','Other'])->default('Home');
            $table->enum('is_delivery_address',['yes','no'])->default('no');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->text('device_token')->nullable();
            $table->enum('device_type',['android','iphone'])->default('android');
            $table->string('uuid')->nullable();
            $table->string('ip')->nullable();
            $table->string('os_version')->nullable();
            $table->string('model_name')->nullable();
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
        Schema::dropIfExists('device_details');
    }
}

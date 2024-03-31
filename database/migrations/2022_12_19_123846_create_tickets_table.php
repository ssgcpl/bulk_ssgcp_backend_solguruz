<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->text('message')->nullable();
             $table->enum('status',['pending','acknowledged','resolved'])->default('pending');
            $table->datetime('acknowledged_date')->nullable();
            $table->text('acknowledged_comment');
            $table->datetime('resolved_date')->nullable();
            $table->bigInteger('reason_id')->unsigned();
            $table->foreign('reason_id')->references('id')->on('reasons')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('user_type');
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
        Schema::dropIfExists('tickets');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
             //   $table->enum('status', ['active', 'inactive', 'pending', 'blocked','deleted'])->default('pending')->change();
             DB::statement('ALTER TABLE users MODIFY COLUMN status ENUM("active", "inactive", "pending", "blocked", "deleted") DEFAULT "pending"');
            });
    }
 //   $table->enum('status',['active','inactive'])->default('active');  

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}

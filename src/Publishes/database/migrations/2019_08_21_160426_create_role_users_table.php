<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan make:migration create_role_users_table
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->unique(['user_id','role_id']);
            
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_users');
    }
}

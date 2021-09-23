<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->integer('origin_id')->default(0)->comment('版本原始id');
            $table->integer('update_user_id')->default(0)->comment('記錄更新人員');
            $table->integer('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->default('啟用')->comment('狀態');
            $table->text('note')->nullable()->comment('備註');
            $table->string('email')->comment('Email');
            $table->string('username')->comment('帳號');
            $table->string('realname')->comment('姓名');
            $table->string('password');
            // $table->foreignId('current_team_id')->nullable();
            // $table->text('profile_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

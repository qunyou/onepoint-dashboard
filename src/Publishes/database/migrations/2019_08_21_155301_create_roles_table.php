<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->bigInteger('origin_id')->default(0)->comment('版本原始id');
            $table->text('note')->nullable()->charset('utf8')->comment('備註');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->default('啟用')->charset('utf8')->comment('狀態');
            $table->string('role_name')->charset('utf8')->comment('人員群組名稱');
            
            // 用 jsonb 比較好，舊的資料庫不支援
            // $table->jsonb('permissions')->nullable()->comment('權限設定');
            $table->longText('permissions')->nullable()->charset('utf8')->comment('權限設定');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}

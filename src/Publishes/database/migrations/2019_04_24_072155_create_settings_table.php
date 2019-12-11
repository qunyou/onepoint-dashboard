<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->bigInteger('origin_id')->default(0)->comment('版本原始id');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->charset('utf8')->default('啟用')->comment('狀態');
            $table->string('model', 255)->charset('utf8')->comment('給哪個功能的設定');
            $table->string('type', 255)->charset('utf8')->comment('設定類別');
            $table->string('title', 255)->charset('utf8')->comment('標題');
            $table->string('description', 255)->nullable()->charset('utf8')->comment('說明');
            $table->string('setting_key', 255)->charset('utf8')->comment('設定索引');
            $table->text('setting_value', 255)->nullable()->charset('utf8')->comment('設定值');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}

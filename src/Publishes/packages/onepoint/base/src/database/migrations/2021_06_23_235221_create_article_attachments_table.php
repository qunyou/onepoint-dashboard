<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_attachments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->integer('origin_id')->default(0)->comment('版本原始id');
            $table->integer('update_user_id')->default(0)->comment('記錄更新人員');
            $table->integer('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->default('啟用')->comment('狀態');
            $table->text('note')->nullable()->charset('utf8')->comment('備註');
            $table->integer('article_id')->default(0)->comment('文章關聯');
            $table->string('file_name')->nullable()->charset('utf8')->comment('檔名');
            $table->string('file_size')->nullable()->charset('utf8')->comment('檔案大小');
            $table->string('origin_name')->nullable()->charset('utf8')->comment('原始檔名(不含副檔名)');
            $table->string('file_extention')->nullable()->charset('utf8')->comment('副檔名');
            $table->string('attachment_title')->nullable()->charset('utf8')->comment('附檔標題');
            $table->text('attachment_description')->nullable()->charset('utf8')->comment('附檔說明');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_attachments');
    }
}

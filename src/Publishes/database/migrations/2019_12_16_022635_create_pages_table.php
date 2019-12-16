<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->bigInteger('origin_id')->default(0)->comment('版本原始id');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->default('啟用')->comment('狀態');
            $table->text('note')->nullable()->charset('utf8')->comment('備註');

            $table->integer('click')->default(0)->comment('點擊');
            $table->string('page_title')->charset('utf8')->comment('單頁標題');
            $table->string('page_title_slug')->charset('utf8')->comment('單頁標題slug');
            $table->longText('page_content')->nullable()->charset('utf8')->comment('單頁內容');

            $table->string('html_title')->nullable()->charset('utf8')->comment('網頁標題');
            $table->text('meta_keywords')->nullable()->charset('utf8')->comment('關鍵字');
            $table->text('meta_description')->nullable()->charset('utf8')->comment('網頁敘述');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}

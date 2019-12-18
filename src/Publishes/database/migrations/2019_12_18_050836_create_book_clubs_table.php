<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_clubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->bigInteger('origin_id')->default(0)->comment('版本原始id');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->default('啟用')->comment('狀態');
            $table->text('note')->nullable()->charset('utf8')->comment('備註');
            $table->bigInteger('click')->default(0)->comment('點擊');

            $table->string('book_club_topic')->nullable()->charset('utf8')->comment('讀書會主題');
            $table->string('book_club_topic_slug')->nullable()->charset('utf8')->comment('讀書會主題slug');
            $table->string('instructor')->nullable()->charset('utf8')->comment('指導老師');
            $table->date('start_at')->nullable()->comment('開始時間');
            $table->date('end_at')->nullable()->comment('結束時間');
            $table->text('description')->nullable()->charset('utf8')->comment('說明');
            $table->bigInteger('reader_id')->default(0)->comment('讀者關聯-組長');
            $table->bigInteger('photographer_id')->default(0)->comment('攝影');
            $table->bigInteger('art_designer_id')->default(0)->comment('美術');
            $table->bigInteger('planner_id')->default(0)->comment('企劃');
            $table->bigInteger('executor_id')->default(0)->comment('製作');

            // $table->string('file_size')->nullable()->charset('utf8')->comment('檔案大小');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_clubs');
    }
}

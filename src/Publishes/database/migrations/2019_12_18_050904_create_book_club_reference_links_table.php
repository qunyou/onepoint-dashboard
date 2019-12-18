<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookClubReferenceLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_club_reference_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('old_version')->default(0)->comment('版本備份');
            $table->bigInteger('origin_id')->default(0)->comment('版本原始id');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', ['啟用', '停用'])->default('啟用')->comment('狀態');
            $table->text('note')->nullable()->charset('utf8')->comment('備註');
            // $table->bigInteger('click')->default(0)->comment('點擊');

            $table->bigInteger('book_club_id')->default(0)->comment('讀書會關聯');
            $table->bigInteger('reader_id')->default(0)->comment('讀者關聯');
            $table->text('link_title')->nullable()->charset('utf8')->comment('標題');
            $table->text('url')->nullable()->charset('utf8')->comment('網址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_club_reference_links');
    }
}
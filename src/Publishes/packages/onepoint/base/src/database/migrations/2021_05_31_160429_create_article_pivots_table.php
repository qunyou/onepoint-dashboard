<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlePivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_pivots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('article_id')->default(0)->comment('文章關聯');
            $table->integer('article_category_id')->default(0)->comment('文章分類關聯');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_pivots');
    }
}

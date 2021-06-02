<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_pivots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('news_id')->default(0)->comment('最新消息關聯');
            $table->integer('news_category_id')->default(0)->comment('最新消息分類關聯');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_pivots');
    }
}

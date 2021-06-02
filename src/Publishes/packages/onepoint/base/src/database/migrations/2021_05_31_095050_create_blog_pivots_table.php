<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_pivots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('blog_id')->default(0)->comment('文章關聯');
            $table->integer('blog_category_id')->default(0)->comment('部落格分類關聯');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_pivots');
    }
}

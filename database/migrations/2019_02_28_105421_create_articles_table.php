<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->string('articles_title')->nullable();
            $table->string('articles_description')->nullable();
            $table->text('articles_text')->nullable();
            $table->boolean('articles_active')->nullable();
            $table->string('articles_slug')->nullable();
            $table->integer('articles_created_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}

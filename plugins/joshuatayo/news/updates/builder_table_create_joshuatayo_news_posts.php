<?php namespace JoshuaTayo\News\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoNewsPosts extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_news_posts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->integer('views')->nullable();
            $table->boolean('published');
            $table->timestamp('published_at')->nullable();
            $table->dateTime('last_viewed')->nullable();
            $table->boolean('send_to_subscribers');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_news_posts');
    }
}

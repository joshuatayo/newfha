<?php namespace JoshuaTayo\News\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoNewsPostsTags extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_news_posts_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('tag_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_news_posts_tags');
    }
}

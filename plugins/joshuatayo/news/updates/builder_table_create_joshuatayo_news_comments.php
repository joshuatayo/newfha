<?php namespace JoshuaTayo\News\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoNewsComments extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_news_comments', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('parent_id')->nullable();
            $table->string('name');
            $table->text('content');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_news_comments');
    }
}

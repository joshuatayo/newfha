<?php namespace JoshuaTayo\News\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoNewsSubscribers extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_news_subscribers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('status');
            $table->string('email', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_news_subscribers');
    }
}

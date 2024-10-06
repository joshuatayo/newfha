<?php namespace JoshuaTayo\News\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateJoshuatayoNewsPosts extends Migration
{
    public function up()
    {
        Schema::table('joshuatayo_news_posts', function($table)
        {
            $table->date('published_at')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('joshuatayo_news_posts', function($table)
        {
            $table->timestamp('published_at')->nullable()->unsigned(false)->default(null)->change();
        });
    }
}

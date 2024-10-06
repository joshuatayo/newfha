<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaVideos extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_videos', function($table)
        {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('link', 255);
            $table->date('date')->nullable();
            $table->boolean('is_enabled');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_videos');
    }
}
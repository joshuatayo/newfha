<?php namespace JoshuaTayo\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoEventCategories extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_event_categories', function($table)
        {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->boolean('is_enabled');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_event_categories');
    }
}
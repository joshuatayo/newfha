<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaServices extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_services', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->text('form_fields')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_services');
    }
}

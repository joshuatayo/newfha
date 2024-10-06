<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaSliders extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_sliders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->string('button_name', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_sliders');
    }
}

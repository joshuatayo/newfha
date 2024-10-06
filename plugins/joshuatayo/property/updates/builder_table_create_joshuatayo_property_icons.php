<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyIcons extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_icons', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('name', 255);
            $table->text('svg')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_icons');
    }
}

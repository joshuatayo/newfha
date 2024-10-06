<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyFloorplans extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_floorplans', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('name');
            $table->integer('floor_size')->nullable();
            $table->integer('bedroom')->nullable();
            $table->integer('bathroom')->nullable();
            $table->integer('property_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_floorplans');
    }
}

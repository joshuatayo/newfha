<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyFeatures extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_features', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('name', 255)->nullable();
            $table->string('value', 255)->nullable();
            $table->integer('icon_id')->nullable();
            $table->integer('sorting')->nullable();
            $table->integer('property_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_features');
    }
}

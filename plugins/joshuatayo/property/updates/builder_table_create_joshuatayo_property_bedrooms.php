<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyBedrooms extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_bedrooms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->integer('value')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_bedrooms');
    }
}

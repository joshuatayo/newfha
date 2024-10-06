<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyBathrooms extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_bathrooms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->string('slug', 244)->nullable();
            $table->integer('value')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_bathrooms');
    }
}

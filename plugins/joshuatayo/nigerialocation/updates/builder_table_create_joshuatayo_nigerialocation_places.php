<?php namespace JoshuaTayo\NigeriaLocation\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoNigerialocationPlaces extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_nigerialocation_places', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->integer('city_id')->nullable();
            $table->string('name', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_nigerialocation_places');
    }
}

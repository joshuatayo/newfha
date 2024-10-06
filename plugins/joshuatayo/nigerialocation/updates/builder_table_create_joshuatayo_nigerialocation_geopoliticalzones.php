<?php namespace JoshuaTayo\NigeriaLocation\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoNigerialocationGeopoliticalzones extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_nigerialocation_geopoliticalzones', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('name', 255);
            $table->string('code', 233);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_nigerialocation_geopoliticalzones');
    }
}

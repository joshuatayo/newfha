<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyProperties extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_properties', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('property_ref', 10)->nullable();
            $table->boolean('is_enabled');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->double('price', 10, 2)->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->integer('postcode')->nullable();
            $table->decimal('latitude', 10, 4)->nullable();
            $table->decimal('longitude', 10, 4)->nullable();
            $table->integer('geopoliticalzone_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('place_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('bedroom_id')->nullable();
            $table->integer('bathroom_id')->nullable();
            $table->smallInteger('is_feature');
            $table->integer('views')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_properties');
    }
}

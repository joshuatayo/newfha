<?php namespace JoshuaTayo\Property\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoPropertyPropertiesAmenities extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_property_properties_amenities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('property_id');
            $table->integer('amenity_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_property_properties_amenities');
    }
}

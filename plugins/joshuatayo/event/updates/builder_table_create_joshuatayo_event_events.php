<?php namespace JoshuaTayo\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoEventEvents extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_event_events', function($table)
        {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('image', 255);
            $table->string('location', 255)->nullable();
            $table->string('map_location', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('contact_info')->nullable();
            $table->text('additional_info')->nullable();
            $table->integer('category_id')->nullable();
            $table->boolean('is_enabled');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_event_events');
    }
}
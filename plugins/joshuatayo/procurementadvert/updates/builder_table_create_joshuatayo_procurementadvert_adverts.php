<?php namespace JoshuaTayo\ProcurementAdvert\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoProcurementadvertAdverts extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_procurementadvert_adverts', function($table)
        {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->text('documents')->nullable();
            $table->dateTime('date_added')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->integer('category_id')->nullable();
            $table->boolean('is_enabled');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_procurementadvert_adverts');
    }
}

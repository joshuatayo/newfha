<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaServiceforms extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_serviceforms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('service_id');
            $table->string('form_id', 255);
            $table->text('form_data')->nullable();
            $table->boolean('is_new')->default(1);
            $table->boolean('is_reply')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_serviceforms');
    }
}

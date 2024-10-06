<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaPartners extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_partners', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('name', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_partners');
    }
}

<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaProjects extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_projects', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->smallInteger('is_enabled');
            $table->integer('submenu_id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('content');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_projects');
    }
}

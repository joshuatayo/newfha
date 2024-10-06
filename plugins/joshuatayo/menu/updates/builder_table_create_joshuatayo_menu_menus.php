<?php namespace JoshuaTayo\Menu\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoMenuMenus extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_menu_menus', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_enabled');
            $table->string('name', 255);
            $table->string('url', 255);
            $table->integer('sorting');
            $table->boolean('has_submenu');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_menu_menus');
    }
}

<?php namespace JoshuaTayo\Fha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoFhaMessages extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_fha_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone_number', 255);
            $table->string('subject', 255);
            $table->text('message');
            $table->boolean('new_message')->default(1);
            $table->boolean('is_replied')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_fha_messages');
    }
}

<?php namespace JoshuaTayo\Report\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateJoshuatayoReportReports extends Migration
{
    public function up()
    {
        Schema::create('joshuatayo_report_reports', function($table)
        {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('document')->nullable();
            $table->integer('category_id')->nullable();
            $table->boolean('is_enabled');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('joshuatayo_report_reports');
    }
}
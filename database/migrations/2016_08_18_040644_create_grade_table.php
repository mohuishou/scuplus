<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade', function (Blueprint $table) {
            $table->increments('id');
            $table->char('courseId');
            $table->char('name');
            $table->char('enName');
            $table->char('lessonId');
            $table->char('credit');
            $table->char('courseType');
            $table->char('term');
            $table->unsignedInteger('termId');
            $table->unsignedInteger('grade');
            $table->unsignedInteger('uid');
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at');
            $table->unsignedInteger('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('grade');
    }
}

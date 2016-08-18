<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->increments('id');
            $table->char('college');
            $table->char('courseId');
            $table->char('name');
            $table->char('lessonId');
            $table->char('credit');
            $table->char('examType');
            $table->char('allWeek');
            $table->char('day');
            $table->char('session');
            $table->char('campus');
            $table->char('building');
            $table->char('classroom');
            $table->char('max');
            $table->char('studentNumber');
            $table->char('courseLimit');
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
        Schema::drop('course');
    }
}

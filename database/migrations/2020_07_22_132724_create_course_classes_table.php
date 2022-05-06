<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
//            $table->unsignedBigInteger('class_id')->nullable();
//            $table->foreign('class_id')->references('id')->on('classes');
            $table->integer('class_id');
            $table->unsignedBigInteger('firefighter_id')->nullable();
            $table->foreign('firefighter_id')->references('id')->on('firefighters');
            $table->enum('attendance',['completed','withdraw','no show','enrolled','stand by'])->default('enrolled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_classes');
    }
}

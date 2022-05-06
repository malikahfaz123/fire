<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrerequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prerequisites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certification_id');
            $table->foreign('certification_id')->references('id')->on('certifications')->cascadeOnDelete();
            $table->unsignedBigInteger('pre_req_course_id')->nullable();
            $table->foreign('pre_req_course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('pre_req_certificate_id')->nullable();
            $table->foreign('pre_req_certificate_id')->references('id')->on('certifications');
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
        Schema::dropIfExists('prerequisites');
    }
}

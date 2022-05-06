<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            $table->string('title')->nullable();
            $table->char('short_title',20)->nullable();
            $table->boolean('renewable')->nullable();
            // $table->enum('renewal_period',['1 year','2 year','3 year'])->nullable();
            $table->integer('renewal_period')->nullable();
            $table->integer('no_of_credit_types')->nullable();
             $table->integer('no_of_pre_req_cert')->nullable();
             $table->integer('no_of_pre_req_course')->nullable();
            $table->float('admin_ceu')->default(0)->nullable();
            $table->float('tech_ceu')->default(0)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('certifications');
    }
}

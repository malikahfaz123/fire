<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardedCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awarded_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_id');
            $table->foreign('certificate_id')->references('id')->on('certifications');
            $table->unsignedBigInteger('firefighter_id');
            $table->foreign('firefighter_id')->references('id')->on('firefighters');
            $table->enum('stage',['initial','renewal'])->default('initial');
            $table->date('receiving_date')->nullable();
            $table->date('issue_date');
            $table->date('lapse_date')->nullable();
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
        Schema::dropIfExists('awarded_certificates');
    }
}

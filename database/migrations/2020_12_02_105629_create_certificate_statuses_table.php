<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_statuses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('firefighter_certificates_id');
            $table->foreign('firefighter_certificates_id')->references('id')->on('firefighter_certificates');
            
            $table->unsignedBigInteger('firefighter_id');
            $table->foreign('firefighter_id')->references('id')->on('firefighters');


            $table->date('test_date');
            $table->time('test_time');
            $table->enum('status',['none','passed','failed']);
            $table->boolean('read_status')->default('0');

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
        Schema::dropIfExists('certificate_statuses');
    }
}

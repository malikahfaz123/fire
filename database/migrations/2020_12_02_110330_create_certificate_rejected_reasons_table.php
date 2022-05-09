<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateRejectedReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_rejected_reasons', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('firefighter_certificates_id');
            $table->foreign('firefighter_certificates_id')->references('id')->on('firefighter_certificates');

            $table->unsignedBigInteger('firefighter_id');
            $table->foreign('firefighter_id')->references('id')->on('firefighters');

            $table->string('reason');
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
        Schema::dropIfExists('certificate_rejected_reasons');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatehistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificatehistories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('firefighter_id');
            $table->foreign('firefighter_id')->references('id')->on('firefighters');

            $table->unsignedBigInteger('certificate_id');
            $table->foreign('certificate_id')->references('id')->on('certifications');

            $table->string('operation');
            $table->string('date');
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
        Schema::dropIfExists('certificatehistories');
    }
}

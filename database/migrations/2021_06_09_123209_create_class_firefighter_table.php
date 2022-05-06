<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassFirefighterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_firefighter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable();;
            $table->unsignedBigInteger('firefighter_id');
            $table->float('admin_ceu')->nullable();
            $table->float('tech_ceu')->nullable();
            // foreign keys
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('firefighter_id')->references('id')->on('firefighters');
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
        Schema::dropIfExists('class_firefighter');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            $table->enum('category',['permanent','temporary']);
            $table->string('country_municipal_code');
            $table->string('name');
            $table->unsignedBigInteger('organization')->nullable();
            $table->foreign('organization')->references('id')->on('organizations');
            $table->enum('status',['yes','no']);
            $table->enum('vacancy_status',['available','unavailable'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('mail_address')->nullable();
            $table->string('mail_municipality')->nullable();
            $table->string('mail_state')->nullable();
            $table->string('mail_zipcode')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('physical_municipality')->nullable();
            $table->string('physical_state')->nullable();
            $table->string('physical_zipcode')->nullable();
            $table->string('owner_name')->nullable();
            $table->text('owner_address')->nullable();
            $table->string('owner_city')->nullable();
            $table->string('owner_state')->nullable();
            $table->string('owner_zipcode')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_phone')->nullable();
            $table->string('signator')->nullable();
            $table->string('signator_phone')->nullable();
            $table->boolean('is_archive')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable();
            $table->foreign('archived_by')->references('id')->on('users');
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
        Schema::dropIfExists('facilities');
    }
}

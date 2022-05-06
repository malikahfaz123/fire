<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            $table->string('country_municipal_code');
            $table->string('name');
            $table->enum('type',['fire department','fire district','government','voc-tech','higher education','other']);
            $table->string('other_type')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('chief_dir_name')->nullable();
            $table->string('chief_dir_phone')->nullable();
            $table->string('auth_sign_name')->nullable();
            $table->string('auth_sign_phone')->nullable();
            $table->string('mail_address')->nullable();
            $table->string('mail_municipality')->nullable();
            $table->string('mail_state')->nullable();
            $table->string('mail_zipcode')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('physical_municipality')->nullable();
            $table->string('physical_state')->nullable();
            $table->string('physical_zipcode')->nullable();
            $table->boolean('is_archive')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable();
            $table->foreign('archived_by')->references('id')->on('users');
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
        Schema::dropIfExists('organizations');
    }
}

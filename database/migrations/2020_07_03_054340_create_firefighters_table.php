<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirefightersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firefighters', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            $table->string('name_suffix')->nullable();
            $table->string('f_name',20)->nullable();
            // $table->string('m_name',20)->default('');
            $table->string('m_name',20)->nullable();
            $table->string('l_name',20)->nullable();
            $table->date('dob')->nullable();
            $table->string('age')->nullable();
            $table->enum('gender',['male', 'female', 'transgender', 'other'])->nullable();
            $table->enum('race',["american indian or alaskan native","asian or pacific islander","black, not of hispanic origin","white, not of hispanic origin","hispanic"])->nullable();
            $table->boolean('postal_mail')->nullable();
            $table->string('address_title')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->boolean('postal_mail_2')->nullable();
            $table->string('address_title_2')->nullable();
            $table->text('address_2')->nullable();
            $table->string('city_2')->nullable();
            $table->string('state_2')->nullable();
            $table->string('zipcode_2')->nullable();
            $table->boolean('postal_mail_3')->nullable();
            $table->string('address_title_3')->nullable();
            $table->text('address_3')->nullable();
            $table->string('city_3')->nullable();
            $table->string('state_3')->nullable();
            $table->string('zipcode_3')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('phone_token')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('cell_phone')->unique()->nullable();
            $table->boolean('cell_phone_verified')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_phone_ext')->nullable();
            $table->string('email_token')->nullable();
            $table->string('work_email')->unique()->nullable();
            $table->boolean('work_email_verified')->nullable();
            $table->string('ssn')->nullable();
            $table->string('ucc')->nullable();
            $table->string('nicet')->nullable();
            $table->string('fema')->nullable();
            $table->string('muni')->nullable();
            $table->string('vol')->nullable();
            $table->string('car')->nullable();
            $table->string('appkey')->nullable();
            $table->boolean('appointed')->default(1)->nullable();
            $table->boolean('is_archive')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable();
            $table->foreign('archived_by')->references('id')->on('users');
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('firefighters');
    }
}

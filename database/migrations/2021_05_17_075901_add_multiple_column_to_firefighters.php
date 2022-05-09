<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnToFirefighters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firefighters', function (Blueprint $table) {
            $table->string('business_name')->after('l_name')->nullable();
            $table->string('county')->after('zipcode')->nullable();
            $table->enum('role_manager',['yes','no'])->after('race')->nullable();
            $table->enum('role',['DCA-Firefighters'])->after('race')->nullable();
            $table->string('cfd_county')->after('race')->nullable();
            $table->string('cfd_name')->after('race')->nullable();
            $table->string('cfdid_no')->after('race')->nullable();
            $table->string('vfdid_no')->after('race')->nullable();
            $table->string('vfd_name')->after('race')->nullable();
            $table->string('vfd_county')->after('race')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firefighters', function (Blueprint $table) {
            //
        });
    }
}

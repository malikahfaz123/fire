<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirefightersReadStatusColumnToAwardedCertificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awarded_certificates', function (Blueprint $table) {
            $table->boolean('firefighters_read_status')->default('0')->after('lapse_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */

    public function down()
    {
        Schema::table('awarded_certificates', function (Blueprint $table) {
            $table->dropForeign('firefighters_read_status');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestStatusColumnToFirefighterCertificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firefighter_certificates', function (Blueprint $table) {
            $table->enum('test_status',['none','passed','failed'])->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firefighter_certificates', function (Blueprint $table) {
            $table->dropForeign('test_status');
        });
    }
}

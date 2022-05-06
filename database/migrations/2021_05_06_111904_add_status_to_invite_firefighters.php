<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToInviteFirefighters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_firefighters', function (Blueprint $table) {
            $table->enum('status',['sent','accepted','revoked'])->after('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invite_firefighters', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

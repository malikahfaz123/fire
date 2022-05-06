<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFirefighters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firefighters', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('email_3')->after('email')->nullable();
            $table->string('email_2')->after('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('firefighter_image')->nullable();
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
//            $table->dropColumn('email');
//            $table->dropColumn('email_verified_at');
//            $table->dropColumn('password');
//            $table->dropColumn('remember_token');
//            $table->dropColumn('firefighter_image');
        });
    }
}

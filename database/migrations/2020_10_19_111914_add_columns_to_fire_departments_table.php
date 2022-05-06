<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFireDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->string('prefix_id');
            $table->string('state');
            $table->integer('no_of_dept_types')->nullable();
            $table->text('email')->nullable();
            $table->text('email_2')->nullable();
            $table->text('email_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->dropColumn(['prefix_id','state','no_of_dept_types','email','email_2','email_3']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstrctvLvlColumnToFirefightersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firefighters', function (Blueprint $table) {
            $table->integer('instructor_level')->nullable()->after('appointed');
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
//            $table->dropColumn('instructor_level');
        });
    }
}

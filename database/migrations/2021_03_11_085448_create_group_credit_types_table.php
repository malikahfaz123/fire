<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupCreditTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_credit_types', function (Blueprint $table) {
            $table->id();
            $table->string('credit_code')->nullable();
            $table->unsignedBigInteger('credit_type_id')->nullable();
            $table->foreign('credit_type_id')->references('id')->on('credit_types');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('group_credit_types');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->text('chief_dir_email')->nullable()->after('chief_dir_name');
            $table->text('chief_dir_email_2')->nullable()->after('chief_dir_name');
            $table->text('chief_dir_email_3')->nullable()->after('chief_dir_email_2');
            $table->text('auth_sign_email')->nullable()->after('auth_sign_name');
            $table->text('auth_sign_email_2')->nullable()->after('auth_sign_name');
            $table->text('auth_sign_email_3')->nullable()->after('auth_sign_email_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            
        });
    }
}
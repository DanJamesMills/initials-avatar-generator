<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AvatarSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(\Config::get('initials-avatar-generator.users_table'), function (Blueprint $table) {
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(\Config::get('initials-avatar-generator.users_table'), function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayedColumnInUserUnitsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_units', function (Blueprint $table) {
            $table->boolean('played')->after('date_completed')->default(0);
        });
        Schema::table('assigned_playlist_units', function (Blueprint $table) {
            $table->boolean('played')->after('date_completed')->default(0);
        });
        Schema::table('module_user', function (Blueprint $table) {
            $table->boolean('played')->after('intro_video_watched')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_units', function (Blueprint $table) {
            $table->dropColumn('played');
        });
        Schema::table('assigned_playlist_units', function (Blueprint $table) {
            $table->dropColumn('played');
        });
        Schema::table('assigned_playlist_units', function (Blueprint $table) {
            $table->dropColumn('played');
        });
    }
}

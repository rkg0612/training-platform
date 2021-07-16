<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignPlaylistGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_playlist_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assigned_playlist_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('assigned_playlist_id')->references('id')->on('assigned_playlists');
            $table->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('assign_playlist_group');
    }
}

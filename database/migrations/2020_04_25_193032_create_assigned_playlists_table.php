<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::create('assigned_playlists', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('playlist_id');
            $table->foreign('playlist_id')
                ->references('id')
                ->on('playlists')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('assignee_id');
            $table->foreign('assignee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamp('due_date')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assigned_playlists');
    }
}

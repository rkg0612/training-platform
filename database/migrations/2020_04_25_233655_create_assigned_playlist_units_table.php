<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedPlaylistUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('assigned_playlist_units', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('assigned_playlist_id');
            $table->foreign('assigned_playlist_id')
                ->references('id')
                ->on('assigned_playlists')
                ->onDelete('cascade');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->timestamp('date_completed');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assigned_playlist_units');
    }
}

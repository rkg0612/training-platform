<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistUnitTable extends Migration
{
    public function up()
    {
        Schema::create('playlist_unit', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('playlist_id');
            $table->foreign('playlist_id')
                ->references('id')
                ->on('playlists')
                ->onDelete('cascade');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('playlist_unit');
    }
}

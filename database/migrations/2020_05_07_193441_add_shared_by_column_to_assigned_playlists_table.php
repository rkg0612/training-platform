<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSharedByColumnToAssignedPlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assigned_playlists', function (Blueprint $table) {
            $table->bigInteger('shared_by')->unsigned()
                ->nullable();
            $table->foreign('shared_by')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assigned_playlists', function (Blueprint $table) {
            $table->dropForeign(['shared_by']);
            $table->dropColumn('shared_by');
        });
    }
}

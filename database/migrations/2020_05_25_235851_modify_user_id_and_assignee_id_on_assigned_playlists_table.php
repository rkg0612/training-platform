<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserIdAndAssigneeIdOnAssignedPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::table('assigned_playlists', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['assignee_id']);

            $table->foreign('assignee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}

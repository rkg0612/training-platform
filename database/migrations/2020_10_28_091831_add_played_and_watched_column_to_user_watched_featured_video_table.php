<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayedAndWatchedColumnToUserWatchedFeaturedVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_watched_featured_videos', function (Blueprint $table) {
            $table->boolean('watched')->default(0)->after('featured_video_id');
            $table->boolean('played')->default(0)->after('watched');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_watched_featured_videos', function (Blueprint $table) {
            $table->dropColumn('watched');
            $table->dropColumn('played');
        });
    }
}

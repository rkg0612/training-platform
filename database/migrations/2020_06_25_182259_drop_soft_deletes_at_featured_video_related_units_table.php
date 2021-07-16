<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSoftDeletesAtFeaturedVideoRelatedUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('featured_video_related_units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}

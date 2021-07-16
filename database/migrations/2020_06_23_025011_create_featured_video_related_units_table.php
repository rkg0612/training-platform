<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedVideoRelatedUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_video_related_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('featured_video_id')->unsigned();
            $table->foreign('featured_video_id')->references('id')
                ->on('featured_videos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->bigInteger('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')
                ->on('units')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('featured_video_related_units');
    }
}

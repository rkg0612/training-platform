<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompetitorIdColumnToInternetShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internet_shops', function (Blueprint $table) {
            $table->bigInteger('competitor_id')->unsigned()->nullable();
            $table->foreign('competitor_id')->references('id')->on('specific_dealer_competitors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internet_shops', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_recordings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('phone_shop_id')->unsigned();
            $table->foreign('phone_shop_id')->references('id')->on('phone_shops')->onDelete('cascade');
            $table->string('value');
            $table->softDeletes();
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
        Schema::dropIfExists('dealer_recordings');
    }
}

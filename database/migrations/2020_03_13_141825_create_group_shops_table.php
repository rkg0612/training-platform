<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group_name')->nullable();
            $table->string('internet_shop_id')->nullable();
            $table->string('phone_shop_id')->nullable();
            $table->bigInteger('assigned_dealer')->unsigned();
            $table->foreign('assigned_dealer')->references('id')->on('dealers')->onDelete('cascade');
            $table->bigInteger('specific_dealer_id')->unsigned();
            $table->foreign('specific_dealer_id')->references('id')->on('specific_dealers')->onDelete('cascade');
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
        Schema::dropIfExists('group_shops');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dealer_id')->unsigned()->nullable();
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('value');
            $table->string('type');
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
        Schema::dropIfExists('dealer_options');
    }
}

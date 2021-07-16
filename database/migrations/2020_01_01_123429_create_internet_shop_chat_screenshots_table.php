<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetShopChatScreenshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internet_shop_chat_screenshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('internet_shop_id')->unsigned();
            $table->foreign('internet_shop_id')->references('id')->on('internet_shops')->onDelete('cascade');
            $table->string('value');
            $table->string('fall_back');
            $table->integer('order_by')->nullable();
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
        Schema::dropIfExists('internet_shop_chat_screenshots');
    }
}

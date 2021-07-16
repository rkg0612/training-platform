<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupShopInternetShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_shop_internet_shop', function (Blueprint $table) {
            $table->unsignedBigInteger('group_shop_id')->nullable();
            $table->foreign('group_shop_id')->references('id')->on('group_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('internet_shop_id')->nullable();
            $table->foreign('internet_shop_id')->references('id')->on('internet_shops')
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
        Schema::dropIfExists('group_shop_internet_shop');
    }
}

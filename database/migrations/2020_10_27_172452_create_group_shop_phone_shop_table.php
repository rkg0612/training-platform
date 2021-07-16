<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupShopPhoneShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_shop_phone_shop', function (Blueprint $table) {
            $table->unsignedBigInteger('group_shop_id')->nullable();
            $table->foreign('group_shop_id')->references('id')->on('group_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('phone_shop_id')->nullable();
            $table->foreign('phone_shop_id')->references('id')->on('phone_shops')
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
        Schema::dropIfExists('group_shop_phone_shop');
    }
}

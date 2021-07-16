<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSecretShopperIdFkOnPhoneShops extends Migration
{
    public function up()
    {
        Schema::table('phone_shops', function (Blueprint $table) {
            $table->dropForeign(['secret_shopper_id']);
            $table->foreign('secret_shopper_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecretShopperIdColumnToPhoneShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_shops', function (Blueprint $table) {
            $table->bigInteger('secret_shopper_id')->unsigned();
            $table->foreign('secret_shopper_Id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}

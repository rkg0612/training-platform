g<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->bigInteger('specific_dealer_id')->unsigned();
            $table->foreign('specific_dealer_id')->references('id')->on('specific_dealers')->onDelete('cascade');
            $table->text('type');
            $table->text('shop_type');
            $table->text('sales_person');
            $table->text('lead_name');
            $table->dateTimeTz('shop_dt');
            $table->text('icg');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_shops');
    }
}

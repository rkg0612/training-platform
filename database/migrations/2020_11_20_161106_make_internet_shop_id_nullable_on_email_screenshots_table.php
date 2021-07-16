<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeInternetShopIdNullableOnEmailScreenshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internet_shop_email_screenshots', function (Blueprint $table) {
            $table->bigInteger('internet_shop_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internet_shop_email_screenshots', function (Blueprint $table) {
            $table->bigInteger('internet_shop_id')->unsigned();
        });
    }
}

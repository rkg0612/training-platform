<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDealerIdToGroupShopsTable extends Migration
{
    public function up()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->bigInteger('dealer_id')->unsigned()->nullable();
            $table->foreign('dealer_id')->references('id')
                ->on('dealers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropColumn(['dealer_id']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDealerIdColumnAtGroupShopsTable extends Migration
{
    public function up()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropColumn(['dealer_id']);
        });
    }

    public function down()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            //
        });
    }
}

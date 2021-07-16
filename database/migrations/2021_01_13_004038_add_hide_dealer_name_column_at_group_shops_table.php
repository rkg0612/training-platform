<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHideDealerNameColumnAtGroupShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->boolean('hide_dealer_name')->default(false)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->dropColumn('hide_dealer_name');
        });
    }
}

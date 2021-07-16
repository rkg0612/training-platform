<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestViewIdToGroupShops extends Migration
{
    public function up()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->string('guest_view_id')->after('id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('group_shops', function (Blueprint $table) {
            $table->dropColumn('guest_view_id');
        });
    }
}

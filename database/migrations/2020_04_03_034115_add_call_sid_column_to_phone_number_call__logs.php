<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallSidColumnToPhoneNumberCallLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_number_call_logs', function (Blueprint $table) {
            $table->string('call_sid')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phone_number_call_logs', function (Blueprint $table) {
            $table->dropColumn('call_sid');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormattedValueToPhoneNumbers extends Migration
{
    public function up()
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->string('formatted_value');
        });
    }

    public function down()
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->dropColumn('formatted_value');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSidColumnToPhoneNumbersTable extends Migration
{
    public function up()
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->string('sid')->after('id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->dropColumn('sid');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropValueColumn extends Migration
{
    public function up()
    {
        Schema::table('dealer_recordings', function (Blueprint $table) {
            $table->dropColumn('duration');
        });

        Schema::table('competitor_recordings', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}

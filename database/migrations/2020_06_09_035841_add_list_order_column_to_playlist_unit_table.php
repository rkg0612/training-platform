<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListOrderColumnToPlaylistUnitTable extends Migration
{
    public function up()
    {
        Schema::table('playlist_unit', function (Blueprint $table) {
            $table->unsignedBigInteger('list_order')->nullable();
        });
    }

    public function down()
    {
        Schema::table('playlist_unit', function (Blueprint $table) {
            $table->dropColumn('list_order');
        });
    }
}

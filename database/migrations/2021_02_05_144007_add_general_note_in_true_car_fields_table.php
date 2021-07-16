<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeneralNoteInTrueCarFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('true_car_fields', function (Blueprint $table) {
            $table->longText('general_note')->nullable()->after('recommendation_sms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('true_car_fields', function (Blueprint $table) {
            $table->dropColumn('general_note');
        });
    }
}

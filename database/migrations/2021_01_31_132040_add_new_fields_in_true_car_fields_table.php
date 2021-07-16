<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInTrueCarFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('true_car_fields', function (Blueprint $table) {
            $table->dateTime('date_time_followup')->nullable()->after('field_leader_name');
            $table->string('recording')->nullable()->after('date_time_followup');
            $table->longText('ab_script_real_payment')->nullable()->after('ab_script_what_payment');
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
            $table->dropColumn('date_time_followup');
            $table->dropColumn('recording');
            $table->dropColumn('ab_script_real_payment');
        });
    }
}

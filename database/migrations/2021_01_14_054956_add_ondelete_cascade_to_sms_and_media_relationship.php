<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOndeleteCascadeToSmsAndMediaRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_medias', function (Blueprint $table) {
            $table->dropForeign(['sms_id']);

            $table->foreign('sms_id')->references('id')
                ->on('phone_number_sms_logs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_medias', function (Blueprint $table) {
        });
    }
}

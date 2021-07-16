<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneNumberSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_number_sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('phone_number_id')->unsigned();
            $table->foreign('phone_number_id')->references('id')->on('phone_numbers')->onDelete('cascade');
            $table->string('value');
            $table->string('from');
            $table->string('to')->nullable();
            $table->dateTime('start_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_number_sms_logs');
    }
}

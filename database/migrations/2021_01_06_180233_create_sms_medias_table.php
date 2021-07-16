<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sms_id');
            $table->foreign('sms_id')->references('id')->on('phone_number_sms_logs');
            $table->string('name');
            $table->string('sid');
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
        Schema::dropIfExists('sms_medias');
    }
}

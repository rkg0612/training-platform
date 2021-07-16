<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internet_shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->boolean('is_dealer')->default('0');
            $table->bigInteger('dealer_id')->unsigned()->nullable();
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->bigInteger('specific_dealer_id')->unsigned();
            $table->foreign('specific_dealer_id')->references('id')->on('specific_dealers')->onDelete('cascade');
            $table->enum('timezone', ['EST', 'CST', 'MST', 'PST', 'AST', 'HST']);

            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->bigInteger('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->boolean('is_shop_new')->default('0');
            $table->string('lead_source')->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('source_link')->nullable();
            $table->string('vehicle_identification_number', 30);
            $table->string('make', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('lead_name');
            $table->string('lead_email');
            $table->string('lead_phone_number');
            $table->string('salesperson_name');
            $table->string('csm_name')->nullable();
            $table->string('call_guide_link')->nullable();
            $table->dateTime('start_at');
            $table->string('shop_duration', 30);

            $table->string('verification_screenshot')->nullable();
            $table->string('verification_screenshot_fallback')->nullable();

            $table->dateTime('call_first_received')->nullable();
            $table->string('call_response_time')->nullable();
            $table->integer('call_attempts')->nullable();

            $table->dateTime('sms_first_received')->nullable();
            $table->string('sms_response_time')->nullable();
            $table->integer('sms_attempts')->nullable();

            $table->dateTime('email_first_received')->nullable();
            $table->string('email_response_time')->nullable();
            $table->integer('email_attempts')->nullable();

            $table->dateTime('email_second_received')->nullable();
            $table->string('email_second_response_time')->nullable();

            $table->dateTime('chat_first_received')->nullable();
            $table->string('chat_response_time')->nullable();
            $table->integer('chat_attempts')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('internet_shops');
    }
}

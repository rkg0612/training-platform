<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPhoneShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_shops', function (Blueprint $table) {
            $table->bigInteger('dealer_id')->unsigned();
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->boolean('shop_type')->default(false);
            $table->string('sales_person_name');
            $table->string('lead_name');
            $table->dateTimeTz('start_date');
            $table->text('inbound_call_grade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phone_shops', function (Blueprint $table) {
            $table->dropForeign([
                'dealer_id',
            ]);
            $table->dropForeign([
                'state_id',
            ]);
            $table->dropColumn([
                'dealer_id', 'state_id', 'shop_type', 'sales_person_name', 'lead_name', 'start_date', 'inbound_call_grade',
            ]);
        });
    }
}

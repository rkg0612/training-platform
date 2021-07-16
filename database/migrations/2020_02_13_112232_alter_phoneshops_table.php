<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPhoneshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_shops', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'shop_type', 'sales_person', 'lead_name', 'shop_dt', 'icg',
            ]);
        });
    }
}

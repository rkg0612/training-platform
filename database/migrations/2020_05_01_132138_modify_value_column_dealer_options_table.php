<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyValueColumnDealerOptionsTable extends Migration
{
    public function up()
    {
        Schema::table('dealer_options', function ($table) {
            $table->text('value')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('dealer_options', function ($table) {
            $table->dropColumn('value');
        });
    }
}

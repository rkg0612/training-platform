<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedModuleUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_module_units', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('assigned_module_id');
            $table->foreign('assigned_module_id')
                ->references('id')
                ->on('assigned_modules')
                ->onDelete('cascade');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->timestamp('date_completed')->nullable();

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
        Schema::dropIfExists('assigned_module_units');
    }
}

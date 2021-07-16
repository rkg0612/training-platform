<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryModuleBuildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_module_build', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_build_id');
            $table->foreign('category_build_id')->references('id')->on('category_build');
            $table->unsignedBigInteger('module_build_id');
            $table->foreign('module_build_id')->references('id')->on('module_build');
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
        Schema::dropIfExists('category_module_build');
    }
}

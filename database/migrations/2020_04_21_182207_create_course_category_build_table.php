<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCategoryBuildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_category_build', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_build_id');
            $table->foreign('course_build_id')->references('id')->on('course_build');
            $table->unsignedBigInteger('category_build_id');
            $table->foreign('category_build_id')->references('id')->on('category_build');
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
        Schema::dropIfExists('course_category_build');
    }
}

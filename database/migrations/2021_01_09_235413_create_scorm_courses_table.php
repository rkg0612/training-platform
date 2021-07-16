<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScormCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scorm_courses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('dealer_id');
            $table->foreign('dealer_id')
                ->references('id')
                ->on('dealers')
                ->onDelete('cascade');

            $table->string('uuid')->nullable();
            $table->string('title')->nullable();

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
        Schema::dropIfExists('scorm_courses');
    }
}

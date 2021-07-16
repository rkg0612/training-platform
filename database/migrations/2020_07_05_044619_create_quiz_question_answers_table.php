<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionAnswersTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_question_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_question_id')->unsigned();
            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('value');
            $table->boolean('is_correct')->default('0');
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
        Schema::dropIfExists('quiz_question_answers');
    }
}

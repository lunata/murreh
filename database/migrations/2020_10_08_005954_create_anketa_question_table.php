<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnketaQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anketa_question', function (Blueprint $table) {
            $table->smallInteger('anketa_id')->unsigned();
            $table->foreign('anketa_id')->references('id')->on('anketas');
            
            $table->smallInteger('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('questions');
            
            $table->smallInteger('answer_id')->unsigned();
            $table->foreign('answer_id')->references('id')->on('answers');
            
            $table->string('answer_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anketa_question');
    }
}

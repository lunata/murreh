<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            //$table->increments('id');
            $table->smallInteger('id')->unsigned()->autoIncrement();
            
            $table->smallInteger('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('questions');
            
            $table->char('code',1);
            $table->string('answer',75);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            //$table->increments('id');
            $table->smallInteger('id')->unsigned()->autoIncrement();
            
            $table->tinyInteger('section_id')->unsigned();
            $table->smallInteger('qsection_id')->unsigned();
            $table->foreign('qsection_id')->references('id')->on('qsections');

            $table->string('question', 150);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}

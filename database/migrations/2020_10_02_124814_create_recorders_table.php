<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recorders', function (Blueprint $table) {
            //$table->increments('id');
            
            $table->smallInteger('id')->unsigned()->autoIncrement();           
            $table->string('name_ru', 150);

            $table->tinyInteger('nationality_id')->unsigned()->nullable();
            $table->foreign('nationality_id')->references('id')->on('nationalities');
            
            $table->tinyInteger('occupation_id')->unsigned()->nullable();
            $table->foreign('occupation_id')->references('id')->on('occupations');
            
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
        Schema::dropIfExists('recorders');
    }
}

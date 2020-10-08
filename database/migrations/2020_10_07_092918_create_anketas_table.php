<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnketasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anketas', function (Blueprint $table) {
            //$table->increments('id');
            $table->smallInteger('id')->unsigned()->autoIncrement(); // MySQL smallint(6)
            
            $table->string('fond_number', 15);

            $table->smallInteger('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            
            $table->smallInteger('place_id')->unsigned();
            $table->foreign('place_id')->references('id')->on('places');
            
            $table->integer('population')->unsigned()->nullable();
            $table->smallInteger('year')->unsigned();

            $table->smallInteger('recorder_id')->unsigned();
            $table->foreign('recorder_id')->references('id')->on('recorders');
            
            $table->smallInteger('informant_id')->unsigned();
            $table->     foreign('informant_id')->references('id')->on('informants');
            
            $table->text('speech_sample')->nullable();
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
        Schema::dropIfExists('anketas');
    }
}

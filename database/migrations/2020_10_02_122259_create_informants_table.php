<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informants', function (Blueprint $table) {
            //$table->increments('id');
            
            $table->smallInteger('id')->unsigned()->autoIncrement();           
            $table->string('name_ru', 150);

            $table->smallInteger('birth_date')->unsigned()->nullable();           

            $table->smallInteger('birth_place_id')->unsigned()->nullable();
            $table->foreign('birth_place_id')->references('id')->on('places');
            
            $table->smallInteger('place_id')->unsigned()->nullable();
            $table->foreign('place_id')->references('id')->on('places');
                        
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
        Schema::dropIfExists('informants');
    }
}

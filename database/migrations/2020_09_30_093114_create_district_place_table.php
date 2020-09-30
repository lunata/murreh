<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_place', function (Blueprint $table) {
            $table->smallInteger('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            
            $table->smallInteger('place_id')->unsigned();
            $table->foreign('place_id')->references('id')->on('places');
            
            $table->smallInteger('include_from')->unsigned()->nullable();
            $table->smallInteger('include_to')->unsigned()->nullable();
            
            $table->primary(['district_id', 'place_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('district_place');
    }
}

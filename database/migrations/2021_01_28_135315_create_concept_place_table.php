<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concept_place', function (Blueprint $table) {
            //$table->id();
            
            $table->smallInteger('concept_id')->unsigned();
            $table->smallInteger('place_id')->unsigned();
            $table->string('code',2);
            $table->string('word', 50);

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
        Schema::dropIfExists('concept_place');
    }
}

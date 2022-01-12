<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('langs', function (Blueprint $table) {
            //$table->increments('id');
            $table->smallInteger('id')->unsigned()->autoIncrement(); // MySQL smallint(6)
            
            $table->string('name_ru', 64);
            
            /* code of language (e.g. 'en', 'ru').  */
            $table->string('code', 20)->unique()->comment = "code of language";

            $table->tinyInteger('sequence_number')->unsigned()->default(0); // MySQL smallint(6)
            // $table->timestamps(); // disabled
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('langs');
    }
}

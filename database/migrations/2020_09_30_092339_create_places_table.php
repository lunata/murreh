<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            //$table->increments('id');
            $table->smallInteger('id')->unsigned()->autoIncrement(); // MySQL smallint(6)
            
            $table->string('name_ru', 150);
            $table->string('name_old_ru', 150)->nullable();
            $table->string('name_krl', 150)->nullable()->collate('utf8_bin');
            $table->string('name_old_krl', 150)->nullable()->collate('utf8_bin');
            
            $table->decimal('latitude', 17,14)->nullable();
            $table->decimal('longitude', 17,14)->nullable();
            $table->integer('population')->unsigned()->nullable();

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
        Schema::dropIfExists('places');
    }
}

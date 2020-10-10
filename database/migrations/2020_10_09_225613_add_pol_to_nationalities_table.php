<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolToNationalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nationalities', function (Blueprint $table) {
            $table->string('name_ru',45)->change();
            $table->renameColumn('name_ru', 'name_ru_m');
            $table->string('name_ru_f',45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nationalities', function (Blueprint $table) {
            $table->string('name_ru_m',150)->change();
            $table->renameColumn('name_ru_m', 'name_ru');
            $table->dropColumn('name_ru_f');
        });
    }
}

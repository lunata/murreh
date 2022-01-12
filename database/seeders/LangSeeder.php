<?php

use Illuminate\Database\Seeder;

use App\Models\Models\Dict\Lang;
//php artisan db:seed

class LangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lang::create([
            'id'=>4,
            'name_ru' => 'собственно карельское',
            'code'    =>'krl',
            'sequence_number' => 4
        ]);
        Lang::create([
            'id'=>5,
            'name_ru' => 'ливвиковское',
            'code'    =>'olo',
            'sequence_number' => 2
        ]);
        Lang::create([
            'id'=>6,
            'name_ru' => 'людиковское',
            'code'    =>'olo',
            'sequence_number' => 3
        ]);
    }
}

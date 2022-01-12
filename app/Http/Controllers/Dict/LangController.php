<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use App\Models\Dict\Dialect;

class LangController extends Controller
{
    /**
     * Gets list of dialects for drop down list in JSON format
     * Test url: /dict/dialect/list?lang_id[]=5
     * 
     * @return JSON response
     */
    public function dialectList(Request $request)
    {

        $dialect_name = '%'.$request->input('q').'%';
        $lang_ids = (array)$request->input('lang_id');
//        $lemma_id = (int)$request->input('lemma_id');

        $list = [];
        $dialects = Dialect::where('name_ru','like', $dialect_name);
        if (sizeof($lang_ids)) {                 
            $dialects = $dialects ->whereIn('lang_id',$lang_ids);
        }
        
        $dialects = $dialects->orderBy('sequence_number')->get();
                         
        foreach ($dialects as $dialect) {
            $list[]=['id'  => $dialect->id, 
                     'text'=> $dialect->name];
        }  
//dd($list);        
//dd(sizeof($dialects));
        return Response::json($list);
    }
}

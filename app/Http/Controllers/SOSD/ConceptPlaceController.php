<?php

namespace App\Http\Controllers\SOSD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Geo\Place;
use App\Models\SOSD\Concept;
use App\Models\SOSD\ConceptCategory;

class ConceptPlaceController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:ref.edit,/sosd/concept_place', ['only' => ['create','store','edit','update','destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $places = Place::whereIn('id', function ($q) {
                            $q->select('place_id')->from('concept_place');
                        })
                        ->orderBy('name_ru')->get();
//dd($concept_categories);        
        return view('sosd.concept_place.index',compact('places'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($place_id)
    {
        $place=Place::findOrFail($place_id);
        
        $concepts=Concept::orderBy('id')->get();
        
        return view('sosd.concept_place.show',compact('place', 'concepts'));
    }
    
    public function compareVocs(Request $request) {
        $search_place1 = (int)$request->input('search_place1') ? (int)$request->input('search_place1') : null;
        $search_place2 = (int)$request->input('search_place2') ? (int)$request->input('search_place2') : null;
        $search_section = $request->input('search_section');
        $search_categories = (array)$request->input('search_categories');
        $by_first = (int)$request->input('by_first');
        
        $place1 = Place::find($search_place1);
        $place2 = Place::find($search_place2);
        
//        $answers = [];
        if ($place1 && $place2) {
            $concepts = Concept::orderBy('id');
            if ($search_section) {
                $concepts = $concepts -> where('concept_category_id', 'like', $search_section.'%');
            }
            if (sizeof($search_categories)) {
                $concepts = $concepts -> whereIn('concept_category_id', $search_categories);
            }
            $concepts = $concepts->get();
/*            $place1_answers=$place1->anketaAnswersString(); 
            $place2_answers= $place2->anketaAnswersString();*/
        } else {
            $concepts=null;
        }

        $place_values = Place::getListInVocs();
        $section_values = ConceptCategory::getSectionList();
        $category_values = ConceptCategory::getList();       
        
        return view('sosd.concept_place.compare_vocs', 
                compact('place_values', 'search_place1', 'search_place2', 'place1', 
                        'place2', 'by_first', 'section_values', 'category_values',                        
                        'concepts', 'search_section', 'search_categories'));         
    }
}

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
        $this->middleware('auth:ref.edit,/sosd/concept_place', [
            'only' => ['create','store','edit','editVoc','update','destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_section = $request->input('search_section');
        $search_category = $request->input('search_category');
        $search_concept = (int)$request->input('search_concept');
        
        $places = Place::whereIn('id', function ($q) {
                            $q->select('place_id')->from('concept_place');
                        });
        if ($search_section) {
            $places = $places->whereIn('id', function ($q) use ($search_section) {
                            $q->select('place_id')->from('concept_place')
                              ->whereIn('concept_id', function ($q2) use ($search_section) {
                                $q2->select('id')->from('concepts')
                                   ->where('concept_category_id', 'like', $search_section.'%');                                  
                              });
                        });                                        
        }
        if ($search_category) {
            $places = $places->whereIn('id', function ($q) use ($search_category) {
                            $q->select('place_id')->from('concept_place')
                              ->whereIn('concept_id', function ($q2) use ($search_category) {
                                $q2->select('id')->from('concepts')
                                   ->where('concept_category_id', $search_category);                                  
                              });
                        });                                        
        }
        if ($search_concept) {
            $places = $places->whereIn('id', function ($q) use ($search_concept) {
                            $q->select('place_id')->from('concept_place')
                              ->where('concept_id', $search_concept);                                  
                        });                                        
        }
                        
        $places = $places->orderBy('name_ru')->get();
        $section_values = ConceptCategory::getSectionList();
        $category_values = [NULL=>'']+ConceptCategory::getList();       
        $concept_values = [NULL=>'']+Concept::getList();       

        return view('sosd.concept_place.index',
                compact('places', 'section_values', 'category_values', 'concept_values',
                        'search_section', 'search_category','search_concept'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $place_id, string $category_id)
    {
        $place = Place::findOrfail($place_id);
        $concepts = Concept::whereConceptCategoryId($category_id)->orderBy('id')->get();
        return view('sosd.concept_place.edit', 
                compact('place', 'concepts', 'category_id'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editVoc(int $concept_id, int $count)
    {
        return view('sosd.concept_place._form_voc_edit', 
                compact('concept_id', 'count'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $place=Place::findOrFail($id);
        $category_id = $request->category_id;
        $words = $request->words;
        
        foreach ($words as $concept_id=>$vocs) {
            $place->concepts()->detach($concept_id);
            foreach ($vocs as $voc) {
                if ($voc['word']) {
                    $place->concepts()->attach($concept_id,['code'=>$voc['code'], 'word'=>$voc['word']]);
                }
            }
        }
        
        $concepts[$category_id]=Concept::whereConceptCategoryId($category_id)->orderBy('id')->get();
//dd($concepts);        
        return view('sosd.concept_place._show',
                compact('place', 'concepts', 'category_id'));
               
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
        
        $concepts = [];
        $categories = ConceptCategory::
                all();
                //take(3)->get();       
        foreach ($categories as $category) {
            $concepts[$category->id]=Concept::whereConceptCategoryId($category->id)->orderBy('id')->get();            
        }
        
        return view('sosd.concept_place.show',
                compact('place', 'concepts', 'categories'));
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

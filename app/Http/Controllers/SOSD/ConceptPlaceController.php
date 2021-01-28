<?php

namespace App\Http\Controllers\SOSD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Geo\Place;
use App\Models\SOSD\Concept;

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
}

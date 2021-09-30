<?php

namespace App\Http\Controllers\SOSD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Response;

use App\Library\Map;
use App\Library\Str;

use App\Models\Geo\Place;

use App\Models\SOSD\Concept;
use App\Models\SOSD\ConceptCategory;
use App\Models\SOSD\ConceptPlace;

class ConceptController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/sosd/concept/', ['only' => 
                    ['create','store','edit','update','destroy']]);
        
        $this->url_args = Concept::urlArgs($request);          
        $this->args_by_get = Str::searchValuesByURL($this->url_args);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $concepts = Concept::search($url_args);

        $numAll = $concepts->count();
        $concepts = $concepts->paginate($url_args['limit_num']);
        
        $category_values = ConceptCategory::getList();
        
        return view('sosd.concept.index', 
                compact('category_values', 'concepts', 
                        'numAll', 'args_by_get', 'url_args'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Redirect::to('/sosd/concept/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Redirect::to('/sosd/concept/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::to('/sosd/concept/');
    }

    public function validateForm(Request $request) {
        $this->validate($request, [
            'concept_category_id'  => 'required|max:4',
            'name'  => 'required|max:150',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $concept = Concept::find($id); 
        $concept_category_values = ConceptCategory::getList();

        return view('sosd.concept.edit', 
                compact('concept', 'concept_category_values', 
                        'args_by_get', 'url_args'));
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
        $this->validateForm($request);
        
        $concept = Concept::find($id);
        $concept->fill($request->all())->save();
        
        return Redirect::to('/sosd/concept'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Redirect::to('/sosd/concept/');
    }
    
    /**
     * Gets list of question sections for drop down list in JSON format
     * Test url: /sosd/concept/list?category_id=A11
     * 
     * @return JSON response
     */
    public function conceptList(Request $request)
    {
        $concept_name = '%'.$request->input('q').'%';
        $category_id = $request->input('category_id');
//dd($section_id);
        $list = [];
        $concepts = Concept::where('name','like', $concept_name);
        if ($category_id) {                 
            $concepts = $concepts->where('concept_category_id', $category_id);
        }
        
        $concepts = $concepts->orderBy('id')->get();
                         
        foreach ($concepts as $concept) {
            $list[]=['id'  => $concept->id, 
                     'text'=> $concept->name];
        }  
//dd($list);        
        return Response::json($list);
    }
    
    public function onMap($id) {
        $id=(int)$id;
        $concept = Concept::findOrFail($id);
        $default_markers = Map::markers();
        $code_places = $markers = [];
        $count=0;
        $vocs=ConceptPlace::whereConceptId($concept->id)->orderBy('code')->get();
        foreach ($vocs as $voc) {
            $code = substr($voc->code, 0, 1);
            if (!isset($markers[$code])) {
                $markers[$code]=$default_markers[$count++];
            }
            $code_places[$code][] = Place::find($voc->place_id);
        }
//dd($answer_places);        
        return view('sosd.concept.map', 
                compact('concept', 'code_places', 'markers')); 
    }
}

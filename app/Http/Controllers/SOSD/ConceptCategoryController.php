<?php

namespace App\Http\Controllers\SOSD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;

use App\Models\SOSD\ConceptCategory;

class ConceptCategoryController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:ref.edit,/sosd/concept_category', ['only' => ['create','store','edit','update','destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $concept_categories = ConceptCategory::orderBy('id')->get();
        $map_dir = ConceptCategory::mapDir();
//dd($concept_categories);        
        return view('sosd.concept_category.index',
                compact('concept_categories', 'map_dir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sosd.concept_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|max:4',
            'name'  => 'required|max:75',
        ]);
        
        $concept_category = ConceptCategory::create($request->all());
        
        return Redirect::to('/sosd/concept_category/')
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::to('/sosd/concept_category/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concept_category = ConceptCategory::find($id); 
        
        return view('sosd.concept_category.edit',compact('concept_category'));
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
        $this->validate($request, [
            'name'  => 'required|max:75',
        ]);
//dd($request);       
        
        if (!$request->reverse_concept_category_id) {
            $request->reverse_concept_category_id = NULL;
        }
        
        $concept_category = ConceptCategory::whereId($id)->first();
        $concept_category->fill($request->all())->save();
        
        return Redirect::to('/sosd/concept_category/')
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
/*        $error = false;
        $status_code = 200;
        $result =[];
        if($id != "") {
            try{
                $concept_category = ConceptCategory::whereId($id)->first();
                if($concept_category){
                    $concept_category_name = $concept_category->name;
                    $concept_category->delete();
                    $result['message'] = \Lang::get('sosd.category_removed', ['name'=>$concept_category_name]);
                }
                else{
                    $error = true;
                    $result['error_message'] = \Lang::get('messages.record_not_exists');
                }
          }catch(\Exception $ex){
                    $error = true;
                    $status_code = $ex->getCode();
                    $result['error_code'] = $ex->getCode();
                    $result['error_message'] = $ex->getMessage();
                }
        }else{
            $error =true;
            $status_code = 400;
            $result['message']='Request data is empty';
        }
        
        if ($error) {
                return Redirect::to('/sosd/concept_category/')
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/sosd/concept_category/')
                  ->withSuccess($result['message']);
        } */
    }
    
    /**
     * Gets list of question sections for drop down list in JSON format
     * Test url: /sosd/concept_category/list?section_id=A
     * 
     * @return JSON response
     */
    public function categoryList(Request $request)
    {

        $category_name = '%'.$request->input('q').'%';
        $section_id = $request->input('section_id');
//dd($section_id);
        $list = [];
        $categories = ConceptCategory::where('name','like', $category_name);
        if ($section_id) {                 
            $categories = $categories->where('id', 'like', $section_id.'%');
        }
        
        $categories = $categories->orderBy('id')->get();
                         
        foreach ($categories as $category) {
            $list[]=['id'  => $category->id, 
                     'text'=> $category->name];
        }  
//dd($list);        
        return Response::json($list);
    }
    
    public function map(string $id, int $map_number)
    {
        $concept_category = ConceptCategory::findOrFail($id);
        $map_dir = ConceptCategory::mapDir();
        
        $places = $concept_category->getPlacesbyNums();
        
        return view('sosd.concept_category.map',
                compact('concept_category', 'map_number', 'map_dir', 'places'));
    }
}

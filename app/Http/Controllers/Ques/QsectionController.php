<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;

use App\Library\Str;

use App\Models\Geo\Place;

use App\Models\Ques\Qsection;

class QsectionController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/ques/qsection/', ['only' => ['create','store','edit','update','destroy']]);
        
        $this->url_args = Qsection::urlArgs($request);                  
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
        
        $qsections = Qsection::search($url_args);

        $numAll = $qsections->count();

        $qsections = $qsections->paginate($url_args['limit_num']);
        
        $section_values = Qsection::getSectionList();
        $map_dir = Qsection::mapDir();
        
        return view('ques.qsection.index',
                    compact('numAll', 'qsections', 'section_values', 
                            'args_by_get', 'url_args', 'map_dir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $section_values = Qsection::getSectionList();

        return view('ques.qsection.create', 
                compact('section_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'title'  => 'required|max:150',
            'section_id' => 'numeric',
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);       
//dd($request->all());        
        $qsection = Qsection::create($request->all());
        
        return Redirect::to('/ques/qsection/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Qsection $qsection)
    {
        return Redirect::to('/ques/qsection/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Qsection $qsection)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
               
        $section_values = Qsection::getSectionList();
        
        $answers = $qsection->answers;
        
        return view('ques.qsection.edit',
                compact('answers', 'section_values', 'qsection', 
                        'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Qsection $qsection)
    {
        $this->validateRequest($request);
        $qsection->fill($request->all())->save();
        
        return Redirect::to('/ques/qsection/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Qsection $qsection)
    {/*
        $error = false;
        $status_code = 200;
        $result =[];
        if($qsection) {
            try{
                $qsection_name = $qsection->title;
                $qsection->delete();
                $result['message'] = \Lang::get('ques.qsection_removed', ['name'=>$qsection_name]);
            }catch(\Exception $ex){
                $error = true;
                $status_code = $ex->getCode();
                $result['error_code'] = $ex->getCode();
                $result['error_message'] = $ex->getMessage();
            }
        } else{
            $error =true;
            $result['error_message'] = \Lang::get('messages.record_not_exists');
        }
        
        if ($error) {
                return Redirect::to('/ques/qsection/'.$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/ques/qsection/'.$this->args_by_get)
                  ->withSuccess($result['message']);
        }
*/    }

    /**
     * Gets list of question sections for drop down list in JSON format
     * Test url: /ques/qsection/list?section_id=2
     * 
     * @return JSON response
     */
    public function qsectionList(Request $request)
    {

        $qsection_name = '%'.$request->input('q').'%';
        $section_id = $request->input('section_id');

        $list = [];
        $qsections = Qsection::where('title','like', $qsection_name);
        if ($section_id) {                 
            $qsections = $qsections ->where('section_id',$section_id);
        }
        
        $qsections = $qsections->orderBy('sequence_number')->get();
                         
        foreach ($qsections as $qsection) {
            $list[]=['id'  => $qsection->id, 
                     'text'=> $qsection->title];
        }  
//dd($list);        
        return Response::json($list);
    }
    
    public function map(string $id, int $map_number)
    {
        $qsection = Qsection::findOrFail($id);
        $map_dir = Qsection::mapDir();
        
//        $places = $concept_category->getPlacesbyNums();
        $places = Place::getListInAnketas();
        ksort($places, SORT_NUMERIC);
//dd($places);        
        return view('ques.qsection.map',
                compact('qsection', 'map_number', 'map_dir', 'places'));
    }
}

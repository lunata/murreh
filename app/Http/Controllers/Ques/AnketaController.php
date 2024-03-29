<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;

use App\Models\User;
use App\Library\Str;

use App\Models\Geo\District;
use App\Models\Geo\Place;
use App\Models\Geo\Region;

use App\Models\Ques\Anketa;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

use App\Models\Person\Informant;
use App\Models\Person\Nationality;
use App\Models\Person\Occupation;
use App\Models\Person\Recorder;

class AnketaController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
//        $this->middleware('auth:edit,/');
        $this->middleware('auth:edit,/ques/anketas/', ['only' => ['create','store','edit','update','destroy']]);
        
        $this->url_args = Anketa::urlArgs($request);                  
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
        
        $anketas = Anketa::search($url_args);

        $numAll = $anketas->count();

        $anketas = $anketas->paginate($url_args['limit_num']);
        
        $district_values = District::getListWithQuantity('anketas', true);
        $informant_values = Informant::getListWithQuantity('anketas', true);
        $place_values = [NULL=>''] + Place::getListWithQuantity('anketas', true);
        $recorder_values = Recorder::getListWithQuantity('anketas', true);
        
        return view('ques.anketa.index',
                    compact('anketas', 'district_values', 'informant_values', 'numAll', 
                            'place_values', 'recorder_values', 'args_by_get', 'url_args'));
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

        $district_values = District::getListWithRegions();
        $informant_values = [NULL => ''] + Informant::getList();
        $place_values = Place::getListWithDistricts();
        $recorder_values = Recorder::getList();
        $region_values = Region::getList();
        $nationality_values = [NULL => ''] + Nationality::getList();
        $occupation_values = [NULL => ''] + Occupation::getList();        
        
        return view('ques.anketa.create', 
                compact('district_values', 'informant_values', 'nationality_values', 
                        'occupation_values', 'place_values', 'recorder_values', 
                        'region_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'fond_number'  => 'required|max:15',
            'year' => 'numeric',
//            'population' => 'numeric',
            'district_id' => 'numeric',
            'place_id' => 'numeric',
            'recorder_id' => 'numeric',
            'information_id' => 'numeric',
        ]);
        $data = $request->all();
        $data['population'] = (int)$data['population'];
        return $data;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $anketa = Anketa::create($this->validateRequest($request));
        
        return Redirect::to('/ques/anketas/'.$anketa->id.'/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Anketa $anketa)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $section_values = Qsection::getSectionListWithQuantity($anketa, !User::checkAccess('edit'));
        $qsection_values = Qsection::getListWithSections(!User::checkAccess('edit'));
        $question_values = Question::getListWithQsections();
        
        if ($url_args['search_question']) {
            $active_question = Question::find($url_args['search_question']);
        } else {
            $active_question = null;
        }
        
        $active_section = $active_question && isset($active_question->section_id) 
                            ? $active_question->section_id 
                            : array_key_first($section_values);
//dd($question_values);        
        return view('ques.anketa.show', 
                compact('active_question', 'active_section', 'anketa', 
                        'section_values', 'qsection_values', 'question_values',
                        'args_by_get', 'url_args'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Anketa $anketa)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $district_values = District::getList();
        $informant_values = [NULL => ''] + Informant::getList();
        $place_values = Place::getList();
        $recorder_values = Recorder::getList();
        $region_values = Region::getList();
        $nationality_values = [NULL => ''] + Nationality::getList();
        $occupation_values = [NULL => ''] + Occupation::getList();        
        
        return view('ques.anketa.edit', 
                compact('anketa', 'district_values', 'informant_values', 'nationality_values', 
                        'occupation_values', 'place_values', 'recorder_values', 
                        'region_values', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anketa $anketa)
    {
        $anketa->fill($this->validateRequest($request))->save();
        
        return Redirect::to('/ques/anketas/'.$anketa->id.'/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anketa $anketa)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($anketa) {
            try{
                $anketa_name = $anketa->fond_number;
                $anketa->delete();
                $result['message'] = \Lang::get('ques.anketa_removed', ['name'=>$anketa_name]);
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
                return Redirect::to('/ques/anketas/')
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/ques/anketas/')
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of anketas for drop down list in JSON format
     * Test url: /ques/anketa/list?without=65
     * 
     * @return JSON response
     */
    public function anketaList(Request $request)
    {

        $name = '%'.$request->input('q').'%';
        $without = $request->input('without');

        $list = [];
        $anketas = Anketa::where('id', '<>', $without)
                         ->where(function ($query) use ($name){
                            $query->whereIn('place_id', function ($q) use ($name) {
                                $q->select('id')->from('places')
                                  ->where('name_ru', 'like', $name);
                            })
                            ->orWhere('fond_number', 'like', $name)
                            ->orWhereIn('district_id', function ($q) use ($name) {
                                $q->select('id')->from('districts')
                                  ->where('name_ru', 'like', $name);
                            });
                         })
                         ->get();
                                
        foreach ($anketas as $anketa) {
            $list[]=['id'  => $anketa->id, 
                     'text'=> $anketa->place->toStringWithDistrict(). ' - '.
                              $anketa->fond_number];
        }  
//dd($list);        
        return Response::json($list);
    }
    
    public function onMap() {
        $places = Place::where('latitude', '>', 0)
                       ->where('longitude', '>', 0)
                       ->whereIn('id', function ($q) {
                           $q->select('place_id')->from('anketas');
                       })
                       ->orderBy('id')->get();
        return view('ques.anketa.map', compact('places')); 
    }
    
    public function getTotal() {
        return Anketa::count();
    }
}

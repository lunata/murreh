<?php

namespace App\Http\Controllers\Person;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

use App\Models\Person\Nationality;
use App\Models\Person\Occupation;
use App\Models\Person\Recorder;

class RecorderController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/');
//        $this->middleware('auth:edit,/person/recorder/', ['only' => ['create','store','edit','update','destroy']]);
        
        $this->url_args = Recorder::urlArgs($request);                  
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
        
        $recorders = Recorder::search($url_args);

        $numAll = $recorders->count();

        $recorders = $recorders->get();
        
        $nationality_values = Nationality::getListWithQuantity('informants');
        $occupation_values = Occupation::getListWithQuantity('informants');
        
        return view('person.recorder.index',
                    compact('nationality_values', 'occupation_values', 'recorders', 
                            'numAll','args_by_get', 'url_args'));
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

        $nationality_values = [NULL => ''] + Nationality::getList();
        $occupation_values = [NULL => ''] + Occupation::getList();
        
        return view('person.recorder.create', 
                compact('nationality_values', 'occupation_values', 
                        'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
//            'nationality_id' => 'numeric',
  //          'occupation_id' => 'numeric',
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
        $recorder = Recorder::create($request->all());
        
        if ($request->from_ajax) {
            return $recorder->id;
        } else {
            return Redirect::to('/person/recorder/'.$this->args_by_get)
                ->withSuccess(\Lang::get('messages.created_success')); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recorder $recorder)
    {
        return Redirect::to('/person/recorder/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recorder $recorder)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $nationality_values = [NULL => ''] + Nationality::getList();
        $occupation_values = [NULL => ''] + Occupation::getList();
        
        return view('person.recorder.edit',
                compact('recorder', 'nationality_values', 'occupation_values',
                        'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recorder $recorder)
    {
        $this->validateRequest($request);
        $recorder->fill($request->all())->save();
        
        return Redirect::to('/person/recorder/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recorder $recorder)
    {
        $error = false;
        $result =[];
        if($recorder) {
            try{
                $recorder_name = $recorder->name;
                $recorder->delete();
                $result['message'] = \Lang::get('person.recorder_removed', ['name'=>$recorder_name]);
            } catch(\Exception $ex){
                $error = true;
                $result['error_code'] = $ex->getCode();
                $result['error_message'] = $ex->getMessage();
            }
        } else {
            $error =true;
            $result['error_message'] = \Lang::get('messages.record_not_exists');
        }
        
        if ($error) {
                return Redirect::to('/person/recorder/'.$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/person/recorder/'.$this->args_by_get)
                  ->withSuccess($result['message']);
        }
    }
    
    public function getTotal() {
        return Recorder::count();
    }
}

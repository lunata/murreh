<?php

namespace App\Http\Controllers\Person;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

use App\Models\Geo\Place;

use App\Models\Person\Informant;
use App\Models\Person\Nationality;
use App\Models\Person\Occupation;

class InformantController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/');
//        $this->middleware('auth:edit,/person/informant/', ['only' => ['create','store','edit','update','destroy']]);
        
        $this->url_args = Informant::urlArgs($request);                  
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
        
        $informants = Informant::search($url_args);

        $numAll = $informants->count();

        $informants = $informants->paginate($url_args['limit_num']);
        
        $place_values = Place::getListWithQuantity('informants');
        $nationality_values = Nationality::getListWithQuantity('informants');
        $occupation_values = Occupation::getListWithQuantity('informants');
        
        return view('person.informant.index',
                    compact('informants', 'nationality_values', 'numAll', 
                            'occupation_values', 'place_values',
                            'args_by_get', 'url_args'));
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
        $place_values = [NULL => ''] + Place::getList();
        
        return view('person.informant.create', 
                compact('nationality_values', 'occupation_values', 
                        'place_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
//            'birth_date' => 'numeric',
//            'birth_place_id' => 'numeric',
  //          'place_id' => 'numeric',
    //        'nationality_id' => 'numeric',
      //      'occupation_id' => 'numeric',
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
        $informant = Informant::create($request->all());
        
        if ($request->from_ajax) {
            return $informant->id;
        } else {
            return Redirect::to('/person/informant/'.$this->args_by_get)
                ->withSuccess(\Lang::get('messages.created_success'));        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Informant $informant)
    {
        return Redirect::to('/person/informant/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Informant $informant)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $place_values = [NULL => ''] + Place::getList();
        $nationality_values = [NULL => ''] + Nationality::getList();
        $occupation_values = [NULL => ''] + Occupation::getList();        
        
        return view('person.informant.edit',
                compact('informant', 'nationality_values', 'occupation_values',
                        'place_values', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Informant $informant)
    {
        $this->validateRequest($request);
        $informant->fill($request->all())->save();
        
        return Redirect::to('/person/informant/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Informant $informant)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($informant) {
            try{
                $informant_name = $informant->name;
                $informant->delete();
                $result['message'] = \Lang::get('person.informant_removed', ['name'=>$informant_name]);
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
                return Redirect::to('/person/informant/')
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/person/informant/')
                  ->withSuccess($result['message']);
        }
    }
}

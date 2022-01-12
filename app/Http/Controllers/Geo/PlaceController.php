<?php

namespace App\Http\Controllers\Geo;

use Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

use App\Models\Dict\Dialect;
use App\Models\Dict\Lang;

use App\Models\Geo\District;
use App\Models\Geo\Place;
//use App\Models\Geo\Region;

class PlaceController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/geo/place/', ['only' => ['create','store','edit','update','destroy']]);

        $this->url_args = Place::urlArgs($request);  
        
        $this->args_by_get = Str::searchValuesByURL($this->url_args);
    }

    /**
     * Show the list of places.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $places = Place::search($url_args);

        $numAll = $places->count();

        $places = $places->paginate($url_args['limit_num']);
        
//        $region_values = Region::getListWithQuantity('places');
        $district_values = District::getListWithQuantity('places');

        return view('geo.place.index', 
                    compact('places', 'district_values', //, 'region_values'
                            'numAll', 'args_by_get', 'url_args'));
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
//        $region_values = Region::getList();
        $district_values = [NULL => ''] + District::getList();
        $lang_values = [NULL => ''] + Lang::getList();
        $dialect_values = [NULL => ''] + Dialect::getList();
        
        return view('geo.place.create',
                  compact(['dialect_values', 'district_values', //'region_values',  
                           'lang_values', 'args_by_get', 'url_args']));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
            'name_old_ru'  => 'max:150',
            'name_krl'     => 'max:150',
            'name_old_krl' => 'max:150',
            'dialect_id' => 'numeric',
//            'district_id' => 'required|numeric',
//            'region_id' => 'required|numeric',
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
        $data=$this->validateRequest($request);
        
/*        $place = Place::create($request->except('districts'));        
        $place->saveDistricts($request->districts);*/
        $place = Place::create($data);        
        $place->saveDistricts($data['districts']);
        
        if ($request->from_ajax) {
            return $place->id;
        } else {
            return Redirect::to('/geo/place/'.$this->args_by_get)
                ->withSuccess(\Lang::get('messages.created_success'));        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Place $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        return Redirect::to('/geo/place/'.($this->args_by_get).
                ($this->args_by_get ? '&' : '?').'search_id='.$place->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
//        $region_values = Region::getList();
        $district_values = [NULL => ''] + District::getList();
        $district_value = $place->districtValue();
        $lang_values = [NULL => ''] + Lang::getList();
        $dialect_values = [NULL => ''] + Dialect::getList();
        
        return view('geo.place.edit',
                  compact(['district_value', 'dialect_values', 'district_values', //'region_values',
                           'lang_values', 'place', 'args_by_get', 'url_args']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        $data=$this->validateRequest($request);
//dd($data);        
/*        $place->fill($request->except('districts'))->save();
        $place->saveDistricts($request->districts);*/
        $place->fill($data)->save();
        $place->saveDistricts($data['districts']);
        
        return Redirect::to('/geo/place/'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        $error = false;
        $result =[];
        if($place) {
            try {
                $place_name = $place->name;
/*                if ($place->anketas()->count() >0) {
                    $error = true;
                    $result['error_message'] = \Lang::get('ques.anketa_exists');
                } elseif ($place->informants()->count() >0) {
                    $error = true;
                    $result['error_message'] = \Lang::get('person.informant_exists');
                } else {*/
                    $place->districts()->detach();
                    $place->delete();
                    $result['message'] = \Lang::get('geo.place_removed', ['name'=>$place_name]);
//                }
          } catch(\Exception $ex){
                $error = true;
                $result['error_code'] = $ex->getCode();
                $result['error_message'] = $ex->getMessage();
            }
        } else{
            $error =true;
            $result['error_message'] = \Lang::get('messages.record_not_exists');
        }
        
        if ($error) {
            return Redirect::to('/geo/place/'.($this->args_by_get))
                           ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/geo/place/'.($this->args_by_get))
                           ->withSuccess($result['message']);
        }
    }
    
    public function showMap() {
        $places = Place::where('latitude', '>', 0)
                       ->where('longitude', '>', 0)
                       ->orderBy('id')->get();
        return view('geo.place.map', compact('places')); 
    }
    
    /**
     * Gets list of places for drop down list in JSON format
     * Test url: /geo/place/list?district_id=16
     * 
     * @return JSON response
     */
    public function placeList(Request $request)
    {
        $place_name = '%'.$request->input('q').'%';
        $method_count = $request->input('method_count');
        $district_id = $request->input('district_id');

        $list = [];
        $places = Place::where('name_ru','like', $place_name);
        if ($district_id) {                 
            $places -> whereIn('id', function ($q) use ($district_id) {
                $q->select('place_id')->from('district_place')
                  ->whereDistrictId($district_id);
            });
        }
        
        $places -> orderBy('name_ru');
//dd($places);                         
        foreach ($places->get() as $place) {
            if ($method_count) {
                $count=$place->$method_count()->count();
                if ($count) {
                    $list[]=['id'  => $place->id, 
                             'text'=> $place->name_ru. ' ('.$count.')'];                    
                }
            } else {
                $list[]=['id'  => $place->id, 
                         'text'=> $place->name_ru];
            }
        }  
//dd($list);        
        return Response::json($list);
    }    
}

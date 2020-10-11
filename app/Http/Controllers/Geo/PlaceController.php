<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

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
        
        return view('geo.place.create',
                  compact(['district_values', //'region_values',  
                           'args_by_get', 'url_args']));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
            'name_old_ru'  => 'max:150',
            'name_krl'     => 'max:150',
            'name_old_krl' => 'max:150',
//            'district_id' => 'required|numeric',
//            'region_id' => 'required|numeric',
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
        
        $place = Place::create($request->except('districts'));        
        $place->saveDistricts($request->districts);
        
        return Redirect::to('/geo/place/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.created_success'));        
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
        
        return view('geo.place.edit',
                  compact(['district_value', 'district_values', 'place', //'region_values',
                           'args_by_get', 'url_args']));
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
        $this->validateRequest($request);
        
        $place->fill($request->except('districts'))->save();

        $place->saveDistricts($request->districts);
        
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
}

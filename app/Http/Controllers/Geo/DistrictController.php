<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

use App\Models\Geo\District;
use App\Models\Geo\Place;
use App\Models\Geo\Region;

class DistrictController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/geo/district/', ['only' => ['create','store','edit','update','destroy']]);
        
        $this->url_args = District::urlArgs($request);          
        $this->args_by_get = Str::searchValuesByURL($this->url_args);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $districts = District::search($url_args);

        $numAll = $districts->count();

        $districts = $districts->paginate($url_args['limit_num']);
        
        $region_values = Region::getListWithQuantity('districts');
        
        return view('geo.district.index', compact(
                        'districts', 'region_values', 'numAll', 
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

        $region_values = Region::getList();
        return view('geo.district.create', 
                compact('region_values',  'args_by_get', 'url_args'));
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
            'name_ru'  => 'required|max:150',
            'region_id' => 'numeric',
        ]);
        
        $district = District::create($request->all());
        
        return Redirect::to('/geo/district/'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Geo\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        return Redirect::to('/geo/district/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $region_values = Region::getList();
        
        return view('geo.district.edit', 
                compact('district', 'region_values', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
            'region_id' => 'numeric',
        ]);
        
        $district->fill($request->all())->save();
        
        return Redirect::to('/geo/district/'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        $error = false;
        $result =[];
        if ($district) {
            try {
                $district_name = $district->name;
                $district->delete();
                $result['message'] = \Lang::get('geo.district_removed', ['name'=>$district_name]);
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
            return Redirect::to('/geo/district/'.($this->args_by_get))
                           ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/geo/district/'.($this->args_by_get))
                           ->withSuccess($result['message']);
        }
    }
}

<?php

namespace App\Http\Controllers\Geo;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;
use App\Models\Geo\Region;

class RegionController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/geo/region/', ['only' => 
                    ['create','store','edit','update','destroy']]);
        
        $this->url_args = Region::urlArgs($request);          
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
        
        $regions = Region::search($url_args);

        $numAll = $regions->count();

        $regions = $regions->get();
        
        return view('geo.region.index', 
                compact('regions', 'numAll', 'args_by_get', 'url_args'));
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

        return view('geo.region.create', 
                compact('args_by_get', 'url_args'));
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
        ]);
        
        $region = Region::create($request->all());
        
        return Redirect::to('/geo/region/'.($this->args_by_get).($this->args_by_get ? '&' : '?').'search_id='.$region->id)
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
        return Redirect::to('/geo/region/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('geo.region.edit', 
                compact('region', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
        ]);
        
//        $region = Region::find($id);
        $region->fill($request->all())->save();
        
        return Redirect::to('/geo/region/'.($this->args_by_get).($this->args_by_get ? '&' : '?').'search_id='.$region->id)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $error = false;
        $result =[];
        if ($region) {
            try{
                $region_name = $region->name;
                $region->delete();
                $result['message'] = \Lang::get('geo.region_removed', ['name'=>$region_name]);
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
                return Redirect::to('/geo/region/'.($this->args_by_get))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/geo/region/'.($this->args_by_get))
                  ->withSuccess($result['message']);
        }
    }
}

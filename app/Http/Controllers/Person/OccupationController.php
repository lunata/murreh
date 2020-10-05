<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;
use App\Models\Person\Occupation;

class OccupationController extends Controller
{
    use \App\Traits\Methods\validateRequest;
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/person/occupation/', ['only' => 
                    ['create','store','edit','update','destroy']]);
        
        $this->url_args = Occupation::urlArgs($request);          
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
        
        $occupations = Occupation::search($url_args);

        $numAll = $occupations->count();

        $occupations = $occupations->get();
        
        return view('person.occupation.index', 
                compact('occupations', 'numAll', 'args_by_get', 'url_args'));
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

        return view('person.occupation.create', 
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
        $this->validateRequest($request);
        $occupation = Occupation::create($request->all());
        
        return Redirect::to('/person/occupation/'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Occupation $occupation)
    {
        return Redirect::to('/person/occupation/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Occupation $occupation)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('person.occupation.edit', 
                compact('occupation', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Occupation $occupation)
    {
        $this->validateRequest($request);
        $occupation->fill($request->all())->save();
        
        return Redirect::to('/person/occupation/'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Occupation $occupation)
    {
        $error = false;
        $result =[];
        if ($occupation) {
            try{
                $name = $occupation->name;
                $occupation->delete();
                $result['message'] = \Lang::get('person.occupation_removed', ['name'=>$name]);
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
                return Redirect::to('/person/occupation/'.($this->args_by_get))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/person/occupation/'.($this->args_by_get))
                  ->withSuccess($result['message']);
        }
    }
}

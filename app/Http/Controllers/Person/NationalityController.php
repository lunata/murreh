<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;
use App\Models\Person\Nationality;

class NationalityController extends Controller
{
    use \App\Traits\Methods\validateRequest;
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/person/nationality/', ['only' => 
                    ['create','store','edit','update','destroy']]);
        
        $this->url_args = Nationality::urlArgs($request);          
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
        
        $nationalities = Nationality::search($url_args);

        $numAll = $nationalities->count();

        $nationalities = $nationalities->get();
        
        return view('person.nationality.index', 
                compact('nationalities', 'numAll', 'args_by_get', 'url_args'));
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

        return view('person.nationality.create', 
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
        $nationality = Nationality::create($request->all());
        
        return Redirect::to('/person/nationality/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Nationality $nationality)
    {
        return Redirect::to('/person/nationality/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Nationality $nationality)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('person.nationality.edit', 
                compact('nationality', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nationality $nationality)
    {
        $this->validateRequest($request);
        $nationality->fill($request->all())->save();
        
        return Redirect::to('/person/nationality/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nationality $nationality)
    {
        $error = false;
        $result =[];
        if ($nationality) {
            try{
                $nationality_name = $nationality->name;
                $nationality->delete();
                $result['message'] = \Lang::get('person.nationality_removed', ['name'=>$nationality_name]);
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
                return Redirect::to('/person/nationality/'.$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/person/nationality/'.$this->args_by_get)
                  ->withSuccess($result['message']);
        }
    }
}

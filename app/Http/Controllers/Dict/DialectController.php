<?php

namespace App\Http\Controllers\Dict;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
//use LaravelLocalization;

use App\Library\Str;

//use App\Models\Corpus\Text;
use App\Models\Dict\Dialect;
use App\Models\Dict\Lang;

class DialectController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/dict/dialect/', 
                ['only' => ['create','store','edit','update','destroy']]);
        $this->url_args = Dialect::urlArgs($request);                  
        $this->args_by_get = Str::searchValuesByURL($this->url_args);
    }

    /**
     * Show the list of dialects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $dialects = Dialect::search($url_args);
         
        $numAll = $dialects->count();
        
        $dialects = $dialects->paginate($url_args['limit_num']);

        $lang_values = Lang::getListWithQuantity('dialects');

        return view('dict.dialect.index',
            compact('dialects', 'lang_values', 'numAll', 'args_by_get', 'url_args'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $lang_values = Lang::getList();

        return view('dict.dialect.create',
                  compact('lang_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:255',
            'code' => 'required|max:20'
        ]);
        return $request->all();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dialect = Dialect::create($this->validateRequest($request));
        
        return Redirect::to('/dict/dialect/')
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
        return Redirect::to('/dict/dialect/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $dialect = Dialect::find($id); 
        if (!$dialect) {
            return Redirect::to('/dict/dialect/')
                           ->withErrors('messages.record_not_exists');
        }
        
        $lang_values = Lang::getList();
        
        return view('dict.dialect.edit',
                  compact('dialect', 'lang_values', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dialect = Dialect::find($id);

        $dialect->fill($this->validateRequest($request))->save();
        
        return Redirect::to('/dict/dialect/'.$this->args_by_get)
                       ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($id != "" && $id > 0) {
            try{
                $dialect = Dialect::find($id);
                if($dialect){
                    $dialect_name = $dialect->name;
                    // check if wordforms and gramsets exists with this dialect
                    if ($dialect->wordforms()->count() || $dialect->texts()->count()) {
                        $result['error_message'] = \Lang::get('dialect_can_not_be_removed');
                    } else {
                        $dialect->delete();
                        $result['message'] = \Lang::get('dict.dialect_removed', ['name'=>$dialect_name]);
                    }
                }
                else{
                    $error = true;
                    $result['error_message'] = \Lang::get('record_not_exists');
                }
          }catch(\Exception $ex){
                    $error = true;
                    $status_code = $ex->getCode();
                    $result['error_code'] = $ex->getCode();
                    $result['error_message'] = $ex->getMessage();
                }
        }else{
            $error =true;
            $status_code = 400;
            $result['message']='Request data is empty';
        }
        
        if ($error) {
            return Redirect::to('/dict/dialect/')
                           ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/dict/dialect/')
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of dialects for drop down list in JSON format
     * Test url: /dict/dialect/list?lang_id[]=1
     * 
     * @return JSON response
     */
    public function dialectList(Request $request)
    {

        $dialect_name = '%'.$request->input('q').'%';
        $lang_ids = (array)$request->input('lang_id');
//        $lemma_id = (int)$request->input('lemma_id');

        $list = [];
        $dialects = Dialect::where(function($q) use ($dialect_name){
                            $q->where('name_en','like', $dialect_name)
                              ->orWhere('name_ru','like', $dialect_name);
                         });
        if (sizeof($lang_ids)) {                 
            $dialects = $dialects ->whereIn('lang_id',$lang_ids);
        }
        
        $dialects = $dialects->orderBy('sequence_number')->get();
                         
        foreach ($dialects as $dialect) {
            $list[]=['id'  => $dialect->id, 
                     'text'=> $dialect->name];
        }  
//dd($list);        
//dd(sizeof($dialects));
        return Response::json($list);

/*        $lang_id = (int)$request->input('lang_id');

        $all_dialects = Dialect::getList($lang_id);

        return Response::json($all_dialects);*/
    }
}

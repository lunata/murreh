<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

//use App\Models\Ques\Answer;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

class QuestionController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/ques/question/', ['only' => ['create','store','edit','update','destroy']]);
        
        $this->url_args = Question::urlArgs($request);                  
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
        
        $questions = Question::search($url_args);

        $numAll = $questions->count();

        $questions = $questions->paginate($url_args['limit_num']);
        
        $section_values = Qsection::getSectionListWithQuantity();
        $qsection_values = Qsection::getListWithQuantity();
        
        return view('ques.question.index',
                    compact('numAll', 'questions', 'section_values', 
                            'qsection_values', 'args_by_get', 'url_args'));
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

        $section_values = Qsection::getSectionList();
        $qsection_values = Qsection::getList();

        $answers = [];
        
        return view('ques.question.create', 
                compact('answers', 'section_values', 'qsection_values', 
                        'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'question'  => 'required|max:150',
            'section_id' => 'numeric',
            'qsection_id' => 'numeric',
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
//dd($request->all());        
        $question = Question::create($request->all());
        
        $question->updateAnswers($request->answers);
        
        return Redirect::to('/ques/question/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return Redirect::to('/ques/question/'.$this->args_by_get);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
               
        $section_values = Qsection::getSectionList();
        $qsection_values = Qsection::getList();
        
        $answers = $question->answers;
        
        return view('ques.question.edit',
                compact('answers', 'section_values', 'qsection_values', 'question', 
                        'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $this->validateRequest($request);
        $question->fill($request->all())->save();
        
        $answer_name = $question->updateAnswers($request->answers);
//dd($answer_name);        
        if ($answer_name) { // answer option that cannot be deleted
            return Redirect::to('/ques/question/'.$this->args_by_get)
                           ->withErrors(trans('error.answer_has_anketas', ['name'=>$answer_name]));
        }        
        return Redirect::to('/ques/question/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($question) {
            try{
                $question_name = $question->question;
                $question->delete();
                $result['message'] = \Lang::get('ques.question_removed', ['name'=>$question_name]);
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
                return Redirect::to('/ques/question/'.$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/ques/question/'.$this->args_by_get)
                  ->withSuccess($result['message']);
        }
    }
}

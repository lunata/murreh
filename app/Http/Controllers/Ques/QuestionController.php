<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;
use DB; 

use App\Library\Map;
use App\Library\Str;

use App\Models\Geo\Place;

use App\Models\Ques\Anketa;
use App\Models\Ques\AnketaQuestion;
use App\Models\Ques\Answer;
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
        $this->middleware('auth:edit,/ques/question/', ['except' => ['index','onMap','questionList','show']]);
        
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
        $qsection_values = [NULL=>'']+Qsection::getListWithQuantity();
        $place_values = [NULL=>''] + Place::getListWithQuantity('anketas', true);
        
        return view('ques.question.index',
                    compact('numAll', 'questions', 'section_values', 'place_values', 
                            'qsection_values','args_by_get', 'url_args'));
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

    public function validateRequest(Request $request, $question_id=null) {
        $this->validate($request, [
            'question'  => 'required|max:150',
            'qsection_id' => 'numeric',
        ]);
        $data = $request->all();
        if (!isset($data['sequence_number']) || !$data['sequence_number']) {
            $data['sequence_number']=Question::selectRaw('max(sequence_number) as max')->first()->max;
        }
        if (!isset($data['weight']) || !$data['weight']) {
            $data['weight']=1;
        }
        if (!isset($data['section_id']) || !$data['section_id']) {
            $data['section_id']= Qsection::getSectionId($data['qsection_id']);
        }
        Question::renumerateOthers($data['sequence_number'], $question_id);
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
        $question = Question::create($this->validateRequest($request));
        
        $question->updateAnswers($request->answers);
        
        return Redirect::to('/ques/question/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFromCluster(Request $request)
    {
        $question = Question::create($this->validateRequest($request));
        
        foreach ($request->answers as $answer_id => $info) {
            $answer = Answer::findOrCreate($question->id, $info['answer'], $info['code']);
            $anketas = Anketa::whereIn('place_id', $info['places'])->get();
            foreach ($anketas as $anketa) {
                $anketa->setNewAnswer($question->id, $answer->id, $info['answer']);

            }
        }
        
//dd($request->place_ids);        
        return Redirect::to('/ques/question?search_id='.$question->id)
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        $section_values = Qsection::getSectionList();
        $anketas_without_answers = $question->getAnketasWithoutAnswers();

        return view('ques.question.show', 
                compact('question', 'section_values', 'anketas_without_answers', 
                        'args_by_get', 'url_args'));
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

    public function editAnswer(int $id, int $anketa_id)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

//        $anketa_question = AnketaQuestion::whereAnketa($anketa_id)->whereQuestion($question_id); , 'anketa_question'
        $question = Question::findOrfail($id);
        $anketa = Anketa::findOrfail($anketa_id);
        return view('ques.question.edit_answer', 
                compact('anketa', 'question', 'args_by_get', 'url_args'));
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
        $question->fill($this->validateRequest($request, $question->id))->save();
        
        $answer_name = $question->updateAnswers($request->answers);
//dd($answer_name);        
        if ($answer_name) { // answer option that cannot be deleted
            return Redirect::to('/ques/question/'.$this->args_by_get)
                           ->withErrors(trans('error.answer_has_anketas', ['name'=>$answer_name]));
        }        
        return Redirect::to('/ques/question/'.$this->args_by_get)
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    public function updateAnswer(Request $request, $anketa_id)
    {
        $anketa=Anketa::findOrFail($anketa_id);
        $answers = $request->answers;
        
        $question_id = array_key_first($answers);
        $answer = $answers[$question_id];
        $anketa->questions()->detach($question_id);
        if ($answer['id']) {
            $anketa->questions()->attach($question_id,['answer_id'=>$answer['id'], 'answer_text'=>$answer['text']]);
        }
        
        return Redirect::to('/ques/question/'.$question_id.'/'.$this->args_by_get)
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
                if ($question->anketas()->whereNotIn('question_id', function($q){
                            $q->select('id')->from('questions')->whereSectionId(5);//вопросы кластеризации, их можно удалять
                        })->count() >0) {
//                    $question->anketas()->detach();
                    $error = true;
                    $result['error_message'] = \Lang::get('ques.anketa_exists');
                } else {
                    $question->anketas()->detach();
                    foreach ($question->answers as $answer) {
                        $answer->delete();
                    }
                    $question->delete();
                    $result['message'] = \Lang::get('ques.question_removed', ['name'=>$question_name]);
                }
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
    
    public function onMap($id) {
        $id=(int)$id;
        $question = Question::findOrFail($id);
        $answers = Answer::whereQuestionId($id)->get();
        $default_markers = Map::markers();
        $answer_places = $markers = [];
        $count=0;
        foreach ($answers as $answer) {
            $answer_id = $answer->id;
            $places = Place::where('latitude', '>', 0)
                   ->where('longitude', '>', 0)
                   ->whereIn('id', function ($q) use ($answer_id) {
                       $q->select('place_id')->from('anketas')
                         ->whereIn('id', function ($q2) use ($answer_id) {
                             $q2->select('anketa_id')->from('anketa_question')
                                ->whereAnswerId($answer_id);
                         });
                   })
                   ->orderBy('id');
            if (!$places->count()) { continue; }
            $markers[$default_markers[$count]]=['num'=>$answer->code, 'text'=> $answer->answer, 'count'=>$places->count()];
            foreach ($places->get() as $place) {
                $answer_places[$default_markers[$count]][] = Place::forMap($place->id, null, [$id]);
            }
            $count++;
        }
//dd($markers, $answer_places/*, $cluster_places, $cl_colors*/);        
        return view('ques.question.map', 
                compact('question', 'answer_places', 'markers')); 
    }
    
    /**
     * Gets list of questions for drop down list in JSON format
     * Test url: /ques/question/list?qsection_ids[]=2&qsection_ids[]=5
     * 
     * @return JSON response
     */
    public function questionList(Request $request)
    {
        $question = '%'.$request->input('q').'%';
        $qsection_ids = (array)$request->input('qsection_ids');

        $list = [];
        $questions = Question::where('question','like', $question);
        if (sizeof($qsection_ids)) {                 
            $questions ->whereIn('qsection_id', $qsection_ids);
        }
        
        $questions = $questions->orderBy('sequence_number')->get();
                         
        foreach ($questions as $question) {
            $list[]=['id'  => $question->id, 
                     'text'=> $question->question];
        }  
        return Response::json($list);
    }
    
    public function copy(Request $request, int $from_question_id, int $to_qsection) {
        $answer_text = $request->input('answer_text');
        if (!$answer_text) {
            return "Не задан ответ";
        }
        
        $from_question = Question::findOrFail($from_question_id);
        if (!$from_question) {
            return "Не задан вопрос, из которого копируется.";
        }
        
        $qsection = Qsection::findOrFail($to_qsection);
        if (!$qsection) {
            return "Не задан подраздел, куда копировать.";
        }
        
        $to_question = (int)$request->input('to_question');
        if (!$to_question) {
            $question = Question::firstOrCreate(['section_id'=>$qsection->section_id, 
                'qsection_id'=>$to_qsection, 'question'=>$answer_text]);
            $question->setSequenceNumber();
        } else {
            $question = Question::findOrFail($to_question);            
        }
        
        $to_answer = (int)$request->input('to_answer');
        if (!$to_answer) {
            $code=$question->newCode();
            $answer = Answer::firstOrCreate(['question_id'=>$question->id, 
                        'answer'=>$answer_text]);
            if (!$answer->code) {
                $answer->code = $code;
                $answer->save();
            }
        } else {
            try {
                $answer = Answer::findOrFail($to_answer);   
            } catch (Exception $e) {
                dd('Ответ не найден');   
            }
        }
        
        $anketas = Anketa::whereIn('id', function ($query) use ($from_question_id, $answer_text) {
                    $query ->select('anketa_id')->from('anketa_question')
                           ->whereQuestionId($from_question_id)
                           ->where('answer_text', 'like', $answer_text);
                    })->get();
//dd(to_sql($anketas));                    
//dd($question);                    
        foreach ($anketas as $anketa) {
            if (!$anketa->questions()->whereQuestionId($question->id)->count()) {
                $anketa->questions()->attach($question->id,['answer_id'=>$answer->id, 'answer_text'=>$answer_text]);
            }
        }
//        $question->updateAnswers($request->answers);
        return 'Данные скопированы!';
    }    
    
    public function answersForMerge($id) {
        $question = Question::find((int)$id);
        if (!$question) {
            return Redirect::to('/ques/question/'.$this->args_by_get)
                ->withError('Нет такого вопроса');        
        }
        $answer_values=[NULL=>''];
        foreach ($question->answers as $answer) {
            $answer_values[$answer->id] = $answer->code. ' - '. $answer->answer;
        }
        $url_args = $this->url_args;
        
        return view('ques.question.merge', 
                compact('answer_values', 'question', 'url_args')); 
    }    
    
    public function mergeAnswers($id, Request $request) {
        $question = Question::find((int)$id);
        if (!$question) {
            return Redirect::to('/ques/question/'.$this->args_by_get)
                ->withError('Нет такого вопроса');        
        }
        $answers = (array)$request->input('answers');
//dd($answers);        
        if (!isset($answers[1]) || !$answers[1]) {
            return Redirect::to('/ques/question/'.$this->args_by_get)
                ->withError('Нет первого ответа');                    
        }
        for ($i=2; $i<=sizeof($answers); $i++) {
            if ($answers[$i]) {
                DB::statement("update anketa_question set answer_id='".$answers[1]."' where answer_id='".$answers[$i]."'");
                Answer::find($answers[$i])->delete();
            }
        }
        
        $new_answers = $question->answers->sortBy('code');
        $letters = range('a', 'z');
        for ($i=0; $i<sizeof($new_answers); $i++) {
            if ($new_answers[$i]->code != $letters[$i]) {
                $new_answers[$i]->code = $letters[$i];
                $new_answers[$i]->save();
//                print '<p>'. $new_answers[$i]->code. " -> ". $letters[$i] .'</p>';
            }
        }
//dd($new_answers);        
//exit(1);        
        return Redirect::to('/ques/question/'.$this->args_by_get)
            ->withSuccess('Ответы объединены');        
    }
}

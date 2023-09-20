<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ques\Anketa;
use App\Models\Ques\AnketaQuestion;
use App\Models\Geo\Place;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

class AnketaQuestionController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:edit,/ques/anketas/', ['only' => ['edit','update']]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $anketa_id, int $qsection_id)
    {
        $anketa = Anketa::findOrfail($anketa_id);
        $questions = Question::where('qsection_id', $qsection_id)->orderBy('sequence_number')->get();
        return view('ques.anketa_question.edit', 
                compact('anketa', 'questions', 'qsection_id'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $anketa_id)
    {
        $anketa=Anketa::findOrFail($anketa_id);
        $qsection_id = (int)$request->qsection_id;
        $answers = $request->answers;
        
        foreach ($answers as $question_id=>$answer) {
            $anketa->setNewAnswer($question_id, $answer['id'], $answer['text']);
        }
        
        $questions = Question::getListByQsection($qsection_id);
        
        return view('ques.anketa._question_show', 
                compact('anketa', 'questions', 'qsection_id'));
               
    }
    
    public function compareAnketas(Request $request)
    {
        $search_place1 = (int)$request->input('search_place1') ? (int)$request->input('search_place1') : null;
        $search_place2 = (int)$request->input('search_place2') ? (int)$request->input('search_place2') : null;
        $search_section = (int)$request->input('search_section') ? (int)$request->input('search_section') : null;
        $search_qsections = (array)$request->input('search_qsections');
        
        $place1 = Place::find($search_place1);
        $place2 = Place::find($search_place2);
        
//        $answers = [];
        if ($place1 && $place2) {
            $questions = Question::orderBy('sequence_number');
            if ($search_section) {
                $questions = $questions -> where('section_id', $search_section);
            }
            if (sizeof($search_qsections)) {
                $questions = $questions -> whereIn('qsection_id', $search_qsections);
            }
            $questions = $questions->get();
/*            $place1_answers=$place1->anketaAnswersString(); 
            $place2_answers= $place2->anketaAnswersString();*/
        } else {
            $questions=null;
        }

        $place_values = Place::getListWithDistrictsInAnketas();
        $section_values = Qsection::getSectionList();
        $qsection_values = Qsection::getList();
//        $questions = Question::getListWithQsections();
        
        
        return view('ques.anketa_question.compare_anketas', 
                compact('place_values', 'search_place1', 'search_place2', 'place1', 'place2',                         
                        'section_values', 'qsection_values', 'questions', 'search_section', 'search_qsections')); 
        
    }
    
    /**
     * View list of questions for <anketa_id> (identified by places and fond number)
     * and for <qsection_id>
     * 
     * test: /ques/anketa_question/list_for_copy/5_1
     * 
     * @param int $anketa_id - anketa ID 
     * @param int $qsection_id - question section ID 
     */
    function listForCopy(int $anketa_id, int $qsection_id) {
        $anketa = Anketa::find($anketa_id);
        $question_values = Question::getListWithQsections();
        
        return view('ques.anketa_question._list_for_copy_answers', 
                compact('qsection_id', 'question_values', 'anketa'));
    }
    
    function copyAnswers(int $from_anketa, int $to_anketa, int $qsection_id) {
        $questions = Question::getListByQsection($qsection_id);
        $anketa_from_obj = Anketa::find($from_anketa);
        $anketa = Anketa::find($to_anketa);
        if (!$anketa_from_obj || !$anketa) {
            return null;
        }
        foreach (array_keys($questions) as $question_id) {
            $answer = $anketa_from_obj->getAnswer($question_id);
            $anketa->questions()->detach($question_id);
            if (isset($answer->answer_id)) {
                $anketa->questions()->attach($question_id,
                        ['answer_id'=>$answer->answer_id, 'answer_text'=>$answer->answer_text]);
            }
        }
        
        return view('ques.anketa._question_show', 
                compact('anketa', 'questions', 'qsection_id'));
    }
    
    public function getTotal(string $section) {
        if ($section == 'all') {
            return number_format(AnketaQuestion::count(), 0, ',', ' ');
        } elseif (is_numeric ($section)) {
            return number_format(AnketaQuestion::whereIn('question_id', function($q) use ($section) {
                $q -> select('id')->from('questions')
                   -> whereSectionId($section);
            })->count(), 0, ',', ' ');
        }
    }
}

<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ques\Anketa;
use App\Models\Ques\AnketaQuestion;
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
    public function update(Request $request, $id)
    {
        $anketa=Anketa::findOrFail($id);
        $qsection_id = (int)$request->qsection_id;
        $answers = $request->answers;
        
        foreach ($answers as $question_id=>$answer) {
            $anketa->questions()->detach($question_id);
            if ($answer['id']) {
                $anketa->questions()->attach($question_id,['answer_id'=>$answer['id'], 'answer_text'=>$answer['text']]);
            }
        }
        
        $questions = Question::getListByQsection($qsection_id);
        
        return view('ques.anketa._question_show', 
                compact('anketa', 'questions', 'qsection_id'));
               
    }
}

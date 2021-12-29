<?php

namespace App\Http\Controllers\Ques;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use App\Models\Ques\Answer;
//use App\Models\Ques\Question;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $answer_obj = Answer::findOrCreate((int)$request->question_id, $request->answer, $request->code);
                
        return $answer_obj ? $answer_obj->id : null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Gets list of answers for drop down list in JSON format
     * Test url: /ques/answer/list?question_id=1
     * 
     * @return JSON response
     */
    public function answerList(Request $request)
    {
        $answer = '%'.$request->input('q').'%';
        $question_id = (int)$request->input('question_id');

        $list = [];
        $answers = Answer::where('answer','like', $answer);
        if ($question_id) {                 
            $answers -> whereQuestionId($question_id);
        }
        
        $answers = $answers->orderBy('code')->get();
                         
        foreach ($answers as $answer) {
            $list[]=['id'  => $answer->id, 
                     'text'=> $answer->code.'. '.$answer->answer];
        }  
        return Response::json($list);
    }
}

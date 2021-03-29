<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

//use App\Library\Service;

use App\Models\Ques\Answer;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

class ServiceController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:admin,/');
    }
    
    public function index() {
        return view('service.index');        
    }
    
    public function addSequenceNumberToQuestions() {
exit(0);
        $after_num = 706;
        $insert_num = 1;
        // первоначально нумеруем 
//        $questions = Question::all();
        $questions = Question::where('sequence_number', '>', $after_num)->orderBy('sequence_number')->get();
        
        foreach ($questions as $ques) {
/*            if ($ques->id >$after_num) {
                $ques->sequence_number =  $insert_num+$ques->id;                
            } else {
                $ques->sequence_number =  $ques->id;
            }*/
            $ques->sequence_number =  $insert_num+$ques->sequence_number;
            $ques->save();
        }
        print "done.";
    }
    
    public function addSequenceNumberToQsections() {
exit(0);
        $after_num = 42;
        $insert_num = 3;

        // первоначально нумеруем 
        $qsections = Qsection::all();
//        $qsections = Qsection::where('sequence_number', '>', $after_num)->get();
        
        foreach ($qsections as $qsec) {
            if ($qsec->id >$after_num) {
                $qsec->sequence_number =  $insert_num+$qsec->id;                
            } else {
                $qsec->sequence_number =  $qsec->id;
            }
//            $qsec->sequence_number =  $insert_num+$ques->sequence_number;
            $qsec->save();
        }
        print "done.";
    }
    
    public function splitQsections() {
exit(0);
        $breakdown = [43=>[632,678], 
                      44=>[679,693],
                      45=>[694,770]];
        
        foreach ($breakdown as $qsection_num => $questions) {
            $qsection = Qsection::where('sequence_number', $qsection_num);
            if ($qsection->count() != 1) {
                dd('Нет такого номера раздела или больше одного: $qsection_num');
            }
            
            DB::statement("UPDATE questions SET qsection_id=".$qsection->first()->id
                         ." where sequence_number >=".$questions[0]." and sequence_number<=".$questions[1]);
        }
        print "done.";
    }
    
    /**
     * select question_id, answer, count(*) as count from answers group by question_id, answer having count>1;
     */
    public function mergeAnswers() {
        $answers = Answer::selectRaw("question_id, answer, count(*)")
                         ->groupBy('question_id')
                         ->groupBy('answer')
                         ->havingRaw('count(*) > 1')
                         ->get();
print "<ol>";        
        foreach ($answers as $answer) {
            $dubl_answers = Answer::whereQuestionId($answer->question_id)
                                  ->whereAnswer($answer->answer)
                                  ->orderBy('code')
                                  ->get();
//dd($dubl_answers, $dubl_answers[0], $dubl_answers[1]);    
            $right_answer=$dubl_answers[0]->id;        
/*            for ($i=1; $i<sizeof($dubl_answers); $i++) {
                $query = "UPDATE anketa_question SET answer_id=".$right_answer. " WHERE answer_id=".$dubl_answers[$i]->id;
//print "<p>$query</p>"; 
                DB::statement($query);
                $dubl_answers[$i]->delete();
            }*/
print '<li><a href="http://murreh.krc.karelia.ru/ques/question?search_id='.$dubl_answers[0]->question_id.'">'.$dubl_answers[0]->question_id.'</a></li>';
//exit(0);            
        }
    }
    
}

<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Library\Service;

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
        $after_num = 706;
        $insert_num = 1;
        $questions = Question::where('sequence_number', '>', $after_num)->orderBy('sequence_number')->get();
        
        foreach ($questions as $ques) {
/*            if ($ques->id <$after_num) {
                $ques->sequence_number =  $ques->id;
            } else {
                $ques->sequence_number =  $insert_num+$ques->id;                
            }*/
            $ques->sequence_number =  $insert_num+$ques->sequence_number;
            $ques->save();
        }
        print "done.";
    }
    
}

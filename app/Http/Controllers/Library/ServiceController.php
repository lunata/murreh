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
        $questions = Question::orderBy('id')->get();
        
        foreach ($questions as $ques) {
            if ($ques->id <592) {
                $ques->sequence_number =  $ques->id;
            } else {
                $ques->sequence_number =  23+$ques->id;                
            }
            $ques->save();
        }
        print "done.";
    }
    
}

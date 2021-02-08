<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Storage;

use App\Library\Export;

use App\Models\Ques\Question;

class ExportController extends Controller
{
    public function __construct(Request $request) {
        $this->middleware('auth:admin,/');
    }
    
    public function answersByQuestions(Request $request) {
        $from  = (int)$request->input('from');
        $to  = (int)$request->input('to');
        $dname = 'export/answers/';
        
        for ($i=$from; $i<=$to; $i++) {
            $fcontent = Export::answersByQuestion($i);
            if ($fcontent) {
                Storage::disk('public')->put($dname."/$i.txt", $fcontent);
            }
//exit(0);            
        }
        print "done";
    }

    public function translationsByQuestions(Request $request) {
        $from  = (int)$request->input('from');
        $to  = (int)$request->input('to');
        $url = Storage::disk('public')->path('/'). 'export/translations/';
//dd($url);        
        
        $list = [];
        for ($i=$from; $i<=$to; $i++) {
            $question = Question::whereSequenceNumber($i)->first();
            if (!$question || !$question->question_ru) {
                continue;
            }
            $list[$question->question_ru] = Export::translationsByQuestion($question->id);            
        }
//dd($list);       
        $qfile=fopen($url.'all.csv', 'w');
        foreach (array_keys($list[array_key_first($list)]) as $place_id) {
            $qfiles[$place_id]=fopen($url.$place_id.'.csv', 'w');
        }        
        
        foreach ($list as $question_ru => $places) {
            fputcsv($qfile, [$question_ru] + array_values($places));
            foreach ($places as $place_id=>$place_answers) {  
                fputcsv($qfiles[$place_id], [$question_ru, $place_answers]);
            }
//exit(0);            
        }
        fclose($qfile);
        print "done";
    }

}

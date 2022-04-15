<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Storage;

use App\Library\Export;

use App\Models\Geo\Place;
use App\Models\SOSD\Concept;
use App\Models\SOSD\ConceptCategory;
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
        $th = ['перевод'];
        foreach (array_keys($list[array_key_first($list)]) as $place_id) {
            $qfiles[$place_id]=fopen($url.$place_id.'.csv', 'w');
            $place = Place::find($place_id);
            $th[]=$place->name;
        }        
        $qfile=fopen($url.'all.csv', 'w');
        fputcsv($qfile, $th);
        
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

    public function concepts() {
        $fname = '/export/concepts.html';
//dd($url);                
        $concepts = Concept::orderBy('id')->get();
        $category_values = ConceptCategory::getList();
        
        Storage::disk('public')->put($fname, "<html>\n\t<body>\n\t\t<table>\n\t\t\t<tr>"
                ."<th>".trans('sosd.category')."</th>"
                ."<th>".trans('sosd.concept')."</th>"
                ."<th>".trans('sosd.variants')."</th></tr>");
        foreach ($concepts as $concept) {
            $str = "\t\t\t<tr><td>".$concept->concept_category_id."</td>"
                ."<td>".$concept->name."</td>"
                ."<td>";
            foreach ($concept->allVariants() as $code => $words) {
                foreach ($words as $word => $places) {
                    $str .= "$code=$word: <i>".join(', ', $places)."</i><br> "; 
                }                    
            }
            Storage::disk('public')->append($fname, $str."</td></tr>");
//exit(0);            
        }
        Storage::disk('public')->append($fname, "\t\t</table>\n\t</body>\n</html>");
        print "done";
    }

    public function conceptsByPlaces() {
        $dname = '/export/concepts_by_places/';
        Export::conceptsByPlaces($dname);
        print "done";
    }
}

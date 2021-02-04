<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;
use Carbon\Carbon;

use App\Library\Export;

use App\Models\Corpus\Text;
use App\Models\Dict\Dialect;
use App\Models\Dict\Gram;
use App\Models\Dict\GramCategory;
use App\Models\Dict\Lang;
use App\Models\Dict\Lemma;
use App\Models\Dict\PartOfSpeech;

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

}

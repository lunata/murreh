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
    
    public function exportAnnotationConll() {
        $filename = 'export/conll/annotation.txt';
        
        Storage::disk('public')->put($filename, "# Parts of speech");
        $parts_of_speech = PartOfSpeech::all()->sortBy('name_en');
        foreach ($parts_of_speech as $pos) {
            Storage::disk('public')->append($filename, $pos->name_en. "\t". $pos->code);            
        }
        
        Storage::disk('public')->append($filename, "\n# Lemma features");
        $lemma_feature = new LemmaFeature;
        foreach ($lemma_feature->feas_conll_codes as $name=>$info) {
            $named_keys = [];
            if (preg_match("/^(.+)_id$/", $name, $regs) && is_array(trans('dict.'.$regs[1].'s'))) {
                $named_keys = trans('dict.'.$regs[1].'s');
//dd($named_keys);                
            }
            foreach ($info as $key=>$code) {
                Storage::disk('public')->append($filename, "$name=".(isset($named_keys[$key]) ? $named_keys[$key] : $key)."\t$code");
            }
        }
        
        Storage::disk('public')->append($filename, "\n# Grammatical attributes");
        $gram_categories = GramCategory::all()->sortBy('sequence_number');
        foreach ($gram_categories as $gram_category) {
            $grams = Gram::where('gram_category_id',$gram_category->id)->orderBy('sequence_number')->get();
            foreach ($grams as $gram) {
                Storage::disk('public')->append($filename, $gram_category->name_en. '='. $gram->name_en. "\t". $gram->conll);            
            }
        }
        
        print  '<p><a href="'.Storage::url($filename).'">annotation</a>';            
    }

}

<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

class Anketa extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['fond_number', 'district_id', 'place_id', 'year', 
        'population', 'recorder_id', 'informant_id', 'speech_sample'];
    
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;
    
    // Has To Many Relations
    use \App\Traits\Relations\BelongsTo\District;
    use \App\Traits\Relations\BelongsTo\Informant;
    use \App\Traits\Relations\BelongsTo\Place;
    use \App\Traits\Relations\BelongsTo\Recorder;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Answers;
    use \App\Traits\Relations\BelongsToMany\Questions;

    public static function search(Array $url_args) {
        $objs = self::orderBy('id');

        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        $objs = self::searchStrField($objs, 'fond_number', $url_args['search_fond_number']);
        $objs = self::searchIntField($objs, 'year', $url_args['search_year']);
        $objs = self::searchIntField($objs, 'district_id', $url_args['search_district']);
        $objs = self::searchIntField($objs, 'place_id', $url_args['search_place']);
        $objs = self::searchIntField($objs, 'recorder_id', $url_args['search_recorder']);
        $objs = self::searchIntField($objs, 'informant_id', $url_args['search_informant']);
        $objs = self::searchByAnswer($objs, $url_args['search_answer']);
        $objs = self::searchByQuestion($objs, $url_args['search_question']);
        
        return $objs;
    }
    
    public static function searchByAnswer($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->whereIn('id', function($query) use ($search_value) {
                    $query->select('anketa_id')->from('anketa_question')
                          ->where('answer_id', $search_value);
        });
    }
    
    public static function searchByQuestion($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->whereIn('id', function($query) use ($search_value) {
                    $query->select('anketa_id')->from('anketa_question')
                          ->where('question_id', $search_value);
        });
    }

    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_fond_number' => $request->input('search_fond_number'),
                    'search_year'   => (int)$request->input('search_year') ? (int)$request->input('search_year') : null,
                    'search_district'   => (int)$request->input('search_district') ? (int)$request->input('search_district') : null,
                    'search_place'   => (int)$request->input('search_place') ? (int)$request->input('search_place') : null,
                    'search_recorder'   => (int)$request->input('search_recorder') ? (int)$request->input('search_recorder') : null,
                    'search_informant'   => (int)$request->input('search_informant') ? (int)$request->input('search_informant') : null,
                    'search_answer'   => (int)$request->input('search_answer') ? (int)$request->input('search_answer') : null,
                    'search_question'   => (int)$request->input('search_question') ? (int)$request->input('search_question') : null,
                ];
        
        return $url_args;
    }  
    
    public function getAnswer($question_id) {
/*        $answer = $this->answers->wherePivot('question_id', $question_id);
        if (!$answer) {
            return null;
        }
        if ($answer->pivot->answer_text) {
            return $answer->pivot->answer_text;
        }
*/        
        $question = $this->questions()->where('question_id', $question_id)->first();
//dd($question->pivot->answer_text);        
        if (!$question) {
            return null;
        }
        if ($question->pivot) {
            return $question->pivot;
        }
 /*       return $question->pivot->answer_id;*/
    }
}

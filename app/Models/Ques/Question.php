<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Redirect;

use App\Library\Str;

use App\Models\Ques\Answer;

class Question extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'section_id', 'qsection_id', 'question', 'question_ru', 'sequence_number', 'weight'];
    
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;
    
    // Has To Many Relations
    use \App\Traits\Relations\BelongsToMany\Anketas;
    
    public function getSectionAttribute() {
        if (!$sections = $this->qsection) {
            return null;
        }
        $sections = $this->qsection->getSections();
        return $sections[$this->section_id] ?? null;
    }
    
    public function getVisibleAttribute() {
        if (!$qsection = $this->qsection) {
            return null;
        }
        return $qsection->status ?? null;
    }
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qsection()
    {
        return $this->belongsTo(Qsection::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)
                    ->orderBy('code');
    }
    
    public function setSequenceNumber() {
        if ($this->sequence_number) {
            return;
        }
        $next_sequence_number = $this->qsection->nextQuestionNumber();
        Question::renumerateOthers($next_sequence_number);
        $this->sequence_number = $next_sequence_number;
        $this->save();
    }
    
    public function getAnswerInPlace($place_id) {
        $anketa_question = AnketaQuestion::whereQuestionId($this->id)
                ->whereIn('anketa_id', function($q) use ($place_id) {
                    $q->select('id')->from('anketas')
                      ->wherePlaceId($place_id);
                })->first();
        $answer=Answer::find($anketa_question->answer_id);
        return $answer->code. ', '. $anketa_question->answer_text;
    }
    
    /**
     * get all answers for this question
     * 
     * @return array : <code. answer><answer_text><anketa_id><anketa_obj> 
     */
    public function getAnswerTexts() {
        $out = [];
        
        $answers = Answer::whereQuestionId($this->id)->get();
        foreach ($answers as $answer) {            
//dd($answer);        
            $a = $answer->code.'. '.$answer->answer;
            $out[$a] = [];
            $anketa_answers = AnketaQuestion::whereQuestionId($this->id)
                    ->whereAnswerId($answer->id)->get();
            foreach ($anketa_answers as $anketa_answer) {
                $out[$a][$anketa_answer->answer_text][$anketa_answer->anketa_id]
                    = Anketa::find($anketa_answer->anketa_id);
            }
        }
        ksort($out);
        return $out;
    }

    public function getAnketasWithoutAnswers() {
        $question_id = $this->id;
        $anketas = Anketa::whereNotIn('id', function($q) use ($question_id) {
                                $q->select('anketa_id')->from('anketa_question')
                                  ->whereQuestionId($question_id);
                           })->get();
        return $anketas;
    }
    
    public static function getSectionIDBySubsectionID(Int $subsection_id) {
        $subsection = Qsection::find($subsection_id);
        if (!$subsection) { return NULL; }
        return $subsection->section_id;
/*        
        $sections = self::getSections();
        $subsections = self::getSubsections();
        $section_id = $subsections[$subsection_id][0] ?? null;
        if (!$section_id || !isset($sections[$section_id])) {
            return null;
        }
        return $section_id;*/
    }
    
    /** Gets list of objects
     * 
     * @return Array [1=>'Вологодская обл.',..]
     */
    public static function getList()
    {     
        $objs = self::orderBy('id')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $list[$row->id] = $row->question;
        }
        
        return $list;         
    }    
    
    /** Gets list of answers
     * 
     * @return Array [1=><answer1>,..]
     */
    public function getAnswerList()
    {     
        $list = array();
        foreach ($this->answers as $row) {
            $list[$row->id] = $row->answer;
        }
        
        return [NULL => ''] + $list;         
    }    
    
    /**
     * 
     * @return Array [<question1_id>=><question1_text>,..]
     */
    public static function getListByQsection($qsection_id)
    {     
        $list = array();
        $objs = self::where('qsection_id', $qsection_id)
                    ->orderBy('sequence_number')->get();

        foreach ($objs as $row) {
            $list[$row->id] = [$row->sequence_number, $row->question];
        }
                
        return $list;         
    }
    
    /**
     * 
     * @return Array [<qsection1_id>=>[<question1_id>=><question1_text>,..], ...]
     */
    public static function getListWithQsections()
    {     
        $qsections = Qsection::orderBy('id')->get();
//dd($qsections);        
        $list = array();
        foreach ($qsections as $qsection) {
            $list[$qsection->id] = self::getListByQsection($qsection->id);
        }
                
        return $list;         
    }

    public function newCode() {
        $last_answer = $this->answers->sortBy('code')->last();
//dd($last_answer->code);        
        if (!$last_answer) {
            return 'a';
        } else {
            return ++$last_answer->code;
        }        
    }
    
    public static function renumerateOthers($sequence_number) {
        $questions = Question::where('sequence_number', '>=', $sequence_number)->latest('sequence_number')->get();
        foreach ($questions as $ques) {
            $ques->sequence_number += 1;
            $ques->save();
        }
        
    }
    
    public function updateAnswers($answers) {
        foreach ($answers as $answer_id => $info) {
//dd($answer_id, $info);            
            if ($answer_id == 'new' && $info['answer']) {
                $answer = Answer::findOrCreate($this->id, $info['answer'], $info['code']);
            } elseif ($answer_id != 'new') {
//dd($answer_id);                
                $answer = Answer::find($answer_id);
                if (!$answer) {
                    dd('There is no answer with ID '.$answer_id);
                }
                if (!$info['answer']) {
                    if ($answer->anketas && $answer->anketas()->count()) {
                        return $answer->answer;
                    }
                    $answer->delete();
                } else {
                    $answer->fill($info)->save();
                }
            }
        }
    }

    public static function search(Array $url_args) {
        $objs = self::orderBy('sequence_number');

        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        $objs = self::searchIntField($objs, 'sequence_number', $url_args['search_sequence_number']);
        $objs = self::searchIntField($objs, 'section_id', $url_args['search_section']);
        $objs = self::searchIntField($objs, 'qsection_id', $url_args['search_qsection']);
        $objs = self::searchStrField($objs, 'question', $url_args['search_question']);
        $objs = self::searchByAnswer($objs, $url_args['search_answer']);
        $objs = self::searchByPlace($objs, $url_args['search_place']);
        
        return $objs;
    }

    public static function searchByAnswer($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->whereIn('id', function($query) use ($search_value) {
                    $query->select('question_id')->from('anketa_question')
                          ->where('answer_text', 'like', $search_value);
        });
    }
    
    public static function searchByPlace($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->whereIn('id', function($query) use ($search_value) {
                    $query->select('question_id')->from('anketa_question')
                          ->whereIn('anketa_id', function($q) use ($search_value) {
                              $q->select('id')->from('anketas')
                                ->wherePlaceId($search_value);
                          });
        });
    }
        
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_answer' => $request->input('search_answer'),
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_sequence_number'   => (int)$request->input('search_sequence_number') ? (int)$request->input('search_sequence_number') : null,
                    'search_question' => $request->input('search_question'),
                    'search_place'   => (int)$request->input('search_place') ? (int)$request->input('search_place') : null,
                    'search_section'   => (int)$request->input('search_section') ? (int)$request->input('search_section') : null,
                    'search_qsection'   => (int)$request->input('search_qsection') ? (int)$request->input('search_qsection') : null,
                ];
        
        return $url_args;
    }    
}

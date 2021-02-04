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
    protected $fillable = ['id', 'section_id', 'qsection_id', 'question', 'question_ru', 'sequence_number'];
    
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
        $last_answer = $this->answers->last();
//dd($last_answer->code);        
        if (!$last_answer) {
            return 'a';
        } else {
            return ++$last_answer->code;
        }        
    }
    
    public function updateAnswers($answers) {
        foreach ($answers as $answer_id => $info) {
//dd($answer_id, $info);            
            if ($answer_id == 'new' && $info['answer']) {
                $answer = Answer::create([
                    'question_id' => $this->id,
                    'code' => $info['code'] ? $info['code'] : $this->newCode(), 
                    'answer' => $info['answer']]);
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
        
        return $objs;
    }
    
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_sequence_number'   => (int)$request->input('search_sequence_number') ? (int)$request->input('search_sequence_number') : null,
                    'search_question' => $request->input('search_question'),
                    'search_section'   => (int)$request->input('search_section') ? (int)$request->input('search_section') : null,
                    'search_qsection'   => (int)$request->input('search_qsection') ? (int)$request->input('search_qsection') : null,
                ];
        
        return $url_args;
    }    
}

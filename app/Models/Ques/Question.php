<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

use App\Models\Ques\Answer;

class Question extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'section_id', 'subsection_id', 'question'];
    
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;
    
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
                    ->orderBy('id');
    }
    
    public static function getSectionIDBySubsectionID(Int $subsection_id) {
        $sections = self::getSections();
        $subsections = self::getSubsections();
        $section_id = $subsections[$subsection_id][0] ?? null;
        if (!$section_id || !isset($sections[$section_id])) {
            return null;
        }
        return $section_id;
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
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('id');

        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        $objs = self::searchIntField($objs, 'section_id', $url_args['search_section']);
        $objs = self::searchIntField($objs, 'qsection_id', $url_args['search_qsection']);
        $objs = self::searchStrField($objs, 'question', $url_args['search_question']);
        
        return $objs;
    }
    
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_question' => $request->input('search_question'),
                    'search_section'   => (int)$request->input('search_section') ? (int)$request->input('search_section') : null,
                    'search_qsection'   => (int)$request->input('search_qsection') ? (int)$request->input('search_qsection') : null,
                ];
        
        return $url_args;
    }    
}

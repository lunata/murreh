<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

class Qsection extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'section_id', 'title', 'sequence_number'];
    
    public  $sections = [
                1 => "Социолингвистическая информация",  
                2 => "Фонетика",  
                3 => "Морфология", 
                4 => "Лексика",
              ];
    
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;

    public function getSections() {
        return $this->sections;
    }
    
    public function getSectionAttribute() {
        $sections = $this->sections;
        return $sections[$this->section_id] ?? null;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class)
                    ->orderBy('id');
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
            $list[$row->id] = $row->title;
        }
        
        return $list;         
    }    
    
    /** Gets list of objects
     * 
     * @return Array [1=>'Вологодская обл.',..]
     */
    public static function getSectionList()
    {     
        $qsection = new Qsection;
        $sections = $qsection->getSections();
        
        $list = array();
        foreach ($sections as $section_id=>$title) {
            $list[$section_id] = $title;
        }
        
        return $list;         
    }    
    
    /** Gets list of objects with quantity of relations $method_name
     * 
     * @return array [1=>'Фонетика (199)',..]
     */
    public static function getSectionListWithQuantity($anketa=null)
    {     
        $qsection = new Qsection;
        $sections = $qsection->getSections();
        
        $list = array();
        foreach ($sections as $section_id=>$title) {
            $count=Question::where('section_id',$section_id)->count();
            if ($anketa) {
                $answer_count=$anketa->questions()->where('section_id', $section_id)->count();
                $count = "$answer_count / $count";
            }
            if ($count) {
                $title .= " ($count)";
            }
            $list[$section_id] = $title;
        }
        
        return $list;         
    }
    
    /** Gets list of objects with quantity of relations $method_name
     * 
     * @return Array [1=>'Вологодская обл. (199)',..]
     */
    public static function getListWithQuantity($method_name='questions')
    {     
        $objs = self::orderBy('id')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $count=$row->$method_name()->count();
            $title = $row->title;
            if ($count) {
                $title .= " ($count)";
            }
            $list[$row->id] = $title;
        }
        
        return $list;         
    }
    
    /**
     * 
     * @return Array [1=>[1=>'Вологодская обл.',..], ...]
     */
    public static function getListWithSections()
    {     
        $qsection = new Qsection;
        $sections = $qsection->getSections();
        
        $list = array();
        foreach (array_keys($sections) as $section_id) {
            $list[$section_id] = [];
            $objs = self::where('section_id', $section_id)->orderBy('id')->get();
        
            foreach ($objs as $row) {
                $list[$section_id][$row->id] = $row->title;
            }
        }
                
        return $list;         
    }
    
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_title' => $request->input('search_title'),
                    'search_section'   => (int)$request->input('search_section') ? (int)$request->input('search_section') : null,
                ];
        
        return $url_args;
    }    
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('sequence_number');

        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        $objs = self::searchIntField($objs, 'section_id', $url_args['search_section']);
        $objs = self::searchStrField($objs, 'title', $url_args['search_title']);
        
        return $objs;
    }
    
}

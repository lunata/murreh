<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qsection extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'section_id', 'title'];
    
    public  $sections = [
                1 => "Социолингвистическая информация",  
                2 => "Фонетика",  
                3 => "Морфология", 
                4 => "Лексика",
              ];
    
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
}

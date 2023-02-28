<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    //protected $fillable = ['name_ru', 'code', 'sequence_number'];
    
    // Has Many Relations
    use \App\Traits\Relations\HasMany\Dialects;
    
    public function identifiableName()
    {
        return $this->name;
    }    

    /** Gets name of this lang, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        return $this->name_ru;
    }

    /** Gets ID of this lang by code, takes into account locale.
     * 
     * @return int
     */
    public static function getIDByCode($code) : Int
    {
        $lang = self::where('code',$code)->first();
        if ($lang) {
            return $lang->id;
        }
    }
           
    /** Gets name of this lang by code, takes into account locale.
     * 
     * @return String
     */
    public static function getNameByCode($code) : String
    {
        $lang = self::where('code',$code)->first();
        if ($lang) {
            return $lang->name;
        }
    }
           
    /** Gets name of this lang by code, takes into account locale.
     * 
     * @return String
     */
    public static function getNameByID($id) : String
    {
        $lang = self::where('id',$id)->first();
        if ($lang) {
            return $lang->name;
        }
    }
                
    /** Gets list of languages
     * 
     * @return Array [1=>'Vepsian',..]
     */
    public static function getList($without=[])
    {     
        
        $languages = self::orderBy('sequence_number')->get();
        
        $list = array();
        foreach ($languages as $row) {
            if (!in_array($row->id, $without)) {
                $list[$row->id] = $row->name;
            }
        }
        
        return $list;         
    }
        
    /** Gets list of languages
     * 
     * @return Array [1=>'Vepsian',..]
     */
    public static function getListWithQuantity($method_name)
    {            
        $languages = self::orderBy('sequence_number')->get();
        
        $list = array();
        foreach ($languages as $row) {
            $count=$row->$method_name()->count();
            $name = $row->name;
            if ($count) {
//                $name .= ' ('. number_with_space($count). ')';
                $name .= ' ('.$count. ')';
            }
            $list[$row->id] = $name;
        }
        
        return $list;         
    }
    public function mainDialect() {
        return self::mainDialectByID($this->id);
    }
    
    public static function mainDialectByID($lang_id) {
        switch ($lang_id) {
//            case 1: return 43;
            case 4: return 46;
            case 5: return 44;
            case 6: return 42;
        }
        return NULL;
    }
}

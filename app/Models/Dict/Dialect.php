<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Model;

class Dialect extends Model
{
    protected $connection = 'vepkar';
//    public $timestamps = false;
//    protected $fillable = ['lang_id', 'name_ru', 'code', 'sequence_number'];
    
    // Belongs To Relations
    use \App\Traits\Relations\BelongsTo\Lang;
    
    // Has Many Relations
    use \App\Traits\Relations\HasMany\Places;
    
    /** Gets name of this dialect, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        return $this->name_ru;
    }

    public function getBcodeAttribute() : String
    {
        if (preg_match ("/-(.+)$/", $this->code, $regs)) {
           return $regs[1]; 
        }
        return $this->code;
    }

    /** Gets ID of this dialect by code.
     * 
     * @return int
     */
    public static function getIDByCode($code) : Int
    {
        $dialect = self::where('code',$code)->first();
        if ($dialect) {
            return $dialect->id;
        }
    }
    /** Gets name of dialects  by ID,
     * 
     * @param $id - dialect ID
     * @return string - localizated name of dialect
     */
    public static function getNameByID($id)
    {     
        $dialect = self::find($id);
        if ($dialect) {
            return $dialect->name;
        } else {
            return NULL;
        }
    }

    public static function getByLang($lang_id) {
        return self::where('lang_id', $lang_id)->get();
    }
    
    /** Gets list of dialects for language $lang_id,
     * if $lang_id is empty, gets all dialects
     * 
     * @param $lang_id - language ID
     * @return Array [1=>'Northern Veps',..]
     */
    public static function getList($lang_id=NULL)
    {     
        $dialects = self::orderBy('sequence_number');
        
        if ($lang_id) {
            $dialects = $dialects->where('lang_id',$lang_id);
        }
        
        $dialects = $dialects->get();
        
        $list = array();
        foreach ($dialects as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }
    
    /** Gets list of dialects group by languages
     * 
     * @return Array ['Vepsian' => [1=>'New written Veps',..], ...]
     */
    public static function getGroupedList()
    {
        $langs = self::groupBy('lang_id')->orderBy('lang_id')->get('lang_id');
        
        $list = [];
        foreach ($langs as $row) {
            foreach (self::getList($row->lang_id) as $dialect_title => $dialect_id) {
                $list[Lang::getNameByID($row->lang_id)][$dialect_title] = $dialect_id;
            }
        }
        
        return $list;         
    }
    
    public static function getLangIDByID($dialect_id) {
        $dialect = self::find($dialect_id);
        if (!$dialect) {
            return NULL;
        }
        return $dialect->lang_id;
    }
}
//select lang_id, dialect_id, code, count(*) as count from places, vepkar.dialects where vepkar.dialects.id=dialect_id and places.id in (select place_id from anketas) group by dialect_id order by lang_id, count DESC;
//select name_ru, dialect_id from places where dialect_id=19 and places.id in (select place_id from anketas);

<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_ru_m', 'name_ru_f'];
    
    use \App\Traits\Methods\searchIntField;
    
    use \App\Traits\Methods\urlArgs;
    
    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Recorders;
    use \App\Traits\Relations\HasMany\Informants;

    /** Gets name of this object, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        $column = "name_ru_m";
        $name = $this->{$column};
        return $name;
    }
    
    /** Gets list of objects
     * 
     * @return Array [1=>'Вологодская обл.',..]
     */
    public static function getList()
    {     
        $objs = self::orderBy('name_ru_m')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }    
    /** Gets list of objects with quantity of relations $method_name
     * 
     * @return Array [1=>'Вологодская обл. (199)',..]
     */
    public static function getListWithQuantity($method_name)
    {     
        $objs = self::orderBy('name_ru_m')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $count=$row->$method_name()->count();
            $name = $row->name;
            if ($count) {
                $name .= " ($count)";
            }
            $list[$row->id] = $name;
        }
    }
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('name_ru_m');
        $objs = self::searchByName($objs, $url_args['search_name']);
        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        
        return $objs;
    }
    
    public static function searchByName($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->where(function($query) use ($search_value) {
                    $query->where('name_ru_m', 'like', $search_value)
                          ->orWhere('name_ru_f', 'like', $search_value);
        });
    }
}

<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

class Region extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    
    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Districts;
    
    // Region __has_many__ Places
/*    
    public function places()
    {
        return $this->hasMany(Place::class);
    }*/

    /** Gets list of regions
     * 
     * @return Array [1=>'Вологодская обл.',..]
     */
    public static function getList()
    {     
        $regions = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($regions as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }
    
    /** Gets list of regions with quantity of relations $method_name
     * 
     * @return Array [1=>'Вологодская обл. (199)',..]
     */
    public static function getListWithQuantity($method_name)
    {     
        $regions = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($regions as $row) {
            $count=$row->$method_name()->count();
            $name = $row->name;
            if ($count) {
                $name .= " ($count)";
            }
            $list[$row->id] = $name;
        }
        
        return $list;         
    }
    
    public static function search(Array $url_args) {
        $regions = Region::orderBy('name_ru');
        if ($url_args['search_name']) {
            $regions = $regions->where('name_ru','like', $url_args['search_name']);
        } 

        if ($url_args['search_id']) {
            $regions = $regions->where('id', $url_args['search_id']);
        } 
        
        return $regions;
    }
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'       => (int)$request->input('search_id'),
                    'search_name'    => $request->input('search_name'),
                ];
        
        if (!$url_args['search_id']) {
            $url_args['search_id'] = NULL;
        }
        
        return $url_args;
    }    
}

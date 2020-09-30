<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;
use App\Models\Geo\Region;

class District extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['region_id','name_ru'];
    
    /** Gets name of this corpus, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        $column = "name_ru";
        $name = $this->{$column};
        return $name;
    }
    
    /** Gets Region
     * 
     * District belongs_to Region
     * 
     * @return Relationship, Query Builder
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    } 
    
    // District __has_many__ Places
    public function places()
    {
        return $this->hasMany(Place::class);
    }

    /** Gets list of districts
     * 
     * @return Array [1=>'Бабаевский р-н',..]
     */
    public static function getList()
    {     
        $locale = LaravelLocalization::getCurrentLocale();
        
        $districts = self::orderBy('name_'.$locale)->get();
        
        $list = array();
        foreach ($districts as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }
    
    /** Gets list of districts with quantity of relations $method_name
     * 
     * @return Array [1=>'Бабаевский р-н (199)',..]
     */
    public static function getListWithQuantity($method_name)
    {     
        $locale = LaravelLocalization::getCurrentLocale();
        
        $districts = self::orderBy('name_'.$locale)->get();
        
        $list = array();
        foreach ($districts as $row) {
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
        $regions = District::orderBy('name_ru');
        if ($url_args['search_id']) {
            $regions = $regions->where('id', $url_args['search_id']);
        } 
        
        if ($url_args['search_region']) {
            $regions = $regions->where('region_id', $url_args['search_region']);
        } 
        
        if ($url_args['search_name']) {
            $regions = $regions->where('name_ru','like', $url_args['search_name']);
        } 

        return $regions;
    }
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'      => (int)$request->input('search_id'),
                    'search_name'    => $request->input('search_name'),
                    'search_region'  => (int)$request->input('search_region'),
                ];
        
        if (!$url_args['search_id']) {
            $url_args['search_id'] = NULL;
        }
        
        return $url_args;
    }
}

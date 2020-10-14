<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Library\Str;

//use App\Models\Geo\District;
use App\Models\Person\Informant;
//use App\Models\Geo\Region;

class Place extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['name_ru', 'name_old_ru', 'name_krl', 'name_old_krl', 
                           'latitude', 'longitude', 'population'];
    
    use \App\Traits\Methods\getNameAttribute;

    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Anketas;
    
    public function identifiableName()
    {
        return $this->placeString();//name;
    }    

    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Districts;
    
/*    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }    
*/    
    // Place __has_many__ Informants
    public function informants()
    {
        return $this->hasMany(Informant::class,'birth_place_id');
    }

    /**
     * Gets IDs of dialects for dialect's form field
     *
     * @return Array
     */
    public function districtValue():Array{
        $value = [];
        if ($this->districts) {
            foreach ($this->districts as $district) {
                $value[] = ['id' => [$district->id],
                    'from'=> $district->pivot->include_from,
                    'to'=> $district->pivot->include_to];
            }
        }
        return $value = collect($value)->sortBy('from')->toArray();
    }

    public function districtListToString() {
        $out = $this->districts->pluck('name')->toArray();
        if (!sizeof($out)) {
            return NULL;
        }
        return join(', ',$out);
    }
    
    public function districtNamesWithDates() {
        $out = [];
        foreach ($this->districts as $district) {
            $from = $district->pivot->include_from;
            $to = $district->pivot->include_to;
            $out[$from] = $district->name.($from || $to ? ' ('.$from.'-'.$to.')' : '');
        }
        ksort($out);
        return join(', ',$out);
    }

    public function saveDistricts(Array $districts) {
        $this->districts()->detach();
        
        foreach($districts as $district) {
            if ($district['id']) {
                $this->districts()->attach($district['id'],
                        ['include_from'=>$district['from'], 
                         'include_to'=>$district['to']]);
            }
        }        
    }
    
    /** Gets list of places
     * 
     * @return Array [1=>'Пондала (Pondal), Бабаевский р-н, Вологодская обл.',..]
     */
    public static function getList()
    {     
        $places = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($places as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }
    
    public static function getListWithDistricts()
    {     
        $places = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($places as $row) {
            $list[$row->id] = $row->toStringWithDistrict();
        }
        
        return $list;         
    }
    
    /** Gets list of places
     * 
     * @return Array [1=>'Dialectal texts (199)',..]
     */
    public static function getListWithQuantity($method_name)
    {     
        $places = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($places as $row) {
            $count=$row->$method_name()->count();
            $name = $row->name;
            if ($count) {
                $name .= " ($count)";
            }
            $list[$row->id] = $name;
        }
        
        return $list;         
    }

    /**
     * Gets full information about place
     * 
     * f.e. "Пондала (Pondal), Бабаевский р-н, Вологодская обл."
     * 
     * @param int $lang_id ID of text language for output translation of settlement title, f.e. Pondal
     * 
     * @return String
     */
    
    public function placeString($lang_id='')
    {
        $info = [];
        
        if ($this->name) {
            $info[0] = $this->name
                     . ($this->name_old_ru ? " (".$this->name_old_ru.")" : '')
                     . ($this->name_krl ? ", ".$this->name_krl : '')
                     . ($this->name_krl_ru ? " (".$this->name_krl_ru.")" : '');
        }
        
        if ($this->district) {
            $info[] = $this->district->name;
        }
        
        if ($this->region) {
            $info[] = $this->region->name;
        }
        
        return join(', ', $info);
    }    
    
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_district' => (int)$request->input('search_district') ? (int)$request->input('search_district') : null,
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'     => $request->input('search_name'),
//                    'search_region'   => (int)$request->input('search_region'),
                ];
        
        return $url_args;
    }
    
    public static function search(Array $url_args) {
        $places = self::orderBy('id'); //name_ru

        $places = self::searchByDistrict($places, $url_args['search_district']);
        $places = self::searchByID($places, $url_args['search_id']);
        $places = self::searchByName($places, $url_args['search_name']);
//        $places = self::searchByRegion($places, $url_args['search_region']);
//dd($places->toSql());                                
        return $places;
    }
    
    public static function searchByName($places, $place_name) {
        if (!$place_name) {
            return $places;
        }
        return $places->where(function($q) use ($place_name){
                            $q->where('name_ru','like', $place_name)
                              ->orWhere('name_old_ru','like', $place_name)            
                              ->orWhere('name_krl','like', $place_name)            
                              ->orWhere('name_old_krl','like', $place_name);           
                });
    }
    
    public static function searchByRegion($places, $region_id) {
        if (!$region_id) {
            return $places;
        }
        return $places->where('region_id',$region_id);
    }
    
    public static function searchByDistrict($places, $district_id) {
        if (!$district_id) {
            return $places;
        }
        return $places->whereIn('id', function ($q) use ($district_id) {
                            $q->select('place_id')->from('district_place')
                              ->where('district_id', $district_id);
                        });
    }
    
    public static function searchByID($places, $search_id) {
        if (!$search_id) {
            return $places;
        }
        return $places->where('id',$search_id);
    }
    
    public function countAnketaPlace() {
        return Anketa::where('place_id',$this->id)->count();
    }
    
    public function countInformantPlace() {
        return Informant::where('place_id',$this->id)->count();
    }
    
    public function toStringWithDistrict() {
        $info = $this->name;
        
        if ($this->districtNamesWithDates()) {
            $info .= ' ('. $this->districtNamesWithDates(). ')';
        }
        
        return $info;
    }
}

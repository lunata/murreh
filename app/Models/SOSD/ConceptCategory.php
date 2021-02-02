<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Geo\Place;

class ConceptCategory extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name'];
    
    public $place_nums = [194 => 1,
                          195 => 2,
                          140 => 3,
                          116 => 4,
                          107 => 5,
                          196 => 6,
                          97 => 7,
                          87 => 8,
                          73 => 9,
                          197 => 10,
                          198 => 11,
                          199 => 12,
                          179 => 13,
                          200 => 14,
                          201 => 15,
                          27 => 16,
                          202 => 17,
                          10 => 18,
                          203 => 19,
                          204 => 20,
                          69 => 21,
                          205 => 22,
                          38 => 23,
                          5 => 24,
                          206 => 25,
                          207 => 26,
                          208 => 27,
                          209 => 28,
                          210 => 20,
                          211 => 30];
    
    // Has Many Relations
    use \App\Traits\Relations\HasMany\Concepts;
    
    public function getSectionAttribute() : String
    {
        return trans("sosd.concept_section_".substr($this->id, 0,1));
    }    
    
    public function getPlacesbyNums() {
        $out = [];
        foreach (array_flip($this->place_nums) as $i => $place_id) {
            $place = Place::find($place_id);
            if ($place) {
                $out [$i] = ['id'=> $place->id, 'name'=> $place->name];
            }
        }
        return $out;
    }
    
    public static function mapDir() {
        return '/cluster_maps/';
    }

        /** Gets list dropdown form
     * 
     * @return Array [<key> => <value>,..]
     */
    public static function getList()
    {     
        $objs = self::orderBy('id')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $list[$row->id] = $row->id .'. '. $row->name;
        }
        
        return $list;         
    }
    
    public static function getSectionList() {     
        $list = [];
        foreach(['A', 'B', 'C'] as $l) {
            $list[$l] = trans("sosd.concept_section_".$l);
        }
        return $list;
    }
}

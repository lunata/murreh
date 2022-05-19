<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Geo\Place;

class Concept extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'concept_category_id', 'name'];
    
    use \App\Traits\Methods\searchIntField;    
    use \App\Traits\Methods\urlArgs;

    // Belongs To Relations
    use \App\Traits\Relations\BelongsTo\Category;
    
    public function places()
    {
        return $this->belongsToMany(Place::class, 'concept_place');
    }
    
    public function getSectionAttribute() : String
    {
        return trans("sosd.concept_section_".substr($this->concept_category_id, 0,1));
    }    

    public function idInFormat() {
        return str_pad($this->id, 4, "0", STR_PAD_LEFT);
    }
    
    public function countPlaces() {
        $concept_id=$this->id;
        $places=Place::whereIn('id', function ($q) use ($concept_id) {
                  $q->select('place_id')->from('concept_place')
                    ->whereConceptId($concept_id);
                })->get();
        return sizeof($places);
    }

    public function allVariants() {
        $out = [];
        $vocs=ConceptPlace::whereConceptId($this->id)->orderBy('code')->get();
        foreach ($vocs as $voc) {
            $place = Place::find($voc->place_id);
            $place_name = $place ? $place->name : $voc->place_id;
            $out[$voc->code][$voc->word][]= $place_name;
        }
        return $out;
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
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('id');
        $recs = self::searchByCategory($objs, $url_args['search_category']);
        $objs = self::searchByName($objs, $url_args['search_name']);
        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        
        return $objs;
    }
    
    public static function searchByCategory($recs, $category_id) {
        if (!$category_id) {
            return $recs;
        }
        return $recs->where('concept_category_id',$category_id);
    }
    
    public static function searchByName($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->where(function($query) use ($search_value) {
                    $query->where('name', 'like', $search_value);
        });
    }
    
    public static function getForPlacesCategory($places, $category_ids, $concept_ids, $with_weight=false) {
        $weights = [];
        $categories = ConceptCategory::whereIn('id',$category_ids)->get();

        $answers = [];
        foreach ($places as $place) {
            $answers[$place->id] = [];
            foreach ($categories as $category) {
                $concepts = Concept::whereConceptCategoryId($category->id);
                if ($concept_ids) {
                    $concepts->whereIn('id', $concept_ids);
                }
                foreach ($concepts->get() as $concept) {
                    $pq_answers = self::where('id',$concept->id)
                            ->join('concept_place', 'concepts.id', '=', 'concept_place.concept_id')
                            ->wherePlaceId($place->id)
                            ->get();
                    $out = [];
                    foreach ($pq_answers as $answer) {
                        $code0 = substr($answer->code, 0, 1);
                        $out[$code0] = isset($out[$code0]) 
                                ? $out[$code0].', '. $answer->word
                                : $answer->word;
                    }
                    $answers[$place->id][$category->name][$concept->name] = $out;
                    if ($with_weight) {
                        $weights[$category->name][$concept->name] = 1;
                    }
                }
            }
        }
        return [$answers, $weights];
    }    
}

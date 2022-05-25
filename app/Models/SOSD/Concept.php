<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Geo\Place;

use App\Models\SOSD\ConceptPlace;

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
    
    public function availableAnswers() {
        return ConceptPlace::whereConceptId($this->id)
                ->selectRaw('substring(code, 1, 1) as code1')
                ->orderBy('code1')
                ->distinct()->pluck('code1')->toArray();
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
    
    public static function getForPlacesCategory($places, $category_ids, $concept_ids, $metric=1) {
        $categories = ConceptCategory::whereIn('id',$category_ids)->get();
        $total_concepts = 0;
        $answers = [];
        foreach ($categories as $category) {
            $concepts = Concept::whereConceptCategoryId($category->id);
            if ($concept_ids) {
                $concepts->whereIn('id', $concept_ids);
            }
            $total_concepts += $concepts->count();
            foreach ($concepts->get() as $concept) {
                $available_answers = $concept->availableAnswers();
                foreach ($places as $place) {
                    $answers[$place->id][$category->name][$concept->name] = [];
                    if ($metric == 2) {
                        $pq_answers = ConceptPlace::where('concept_id',$concept->id)
                                ->wherePlaceId($place->id)
                                ->selectRaw('substring(code, 1, 1) as code1')
                                ->groupBy('code1')->orderBy('code1')->pluck('code1')->toArray();
                        foreach ($available_answers as $code) {
                            $answers[$place->id][$category->name][$concept->name][$code] = in_array($code, $pq_answers) ? round(1/sizeof($pq_answers),2) : 0;                        
                        }
                    } else {
                        $pq_answers = ConceptPlace::where('concept_id',$concept->id)
                                ->wherePlaceId($place->id)
                                ->selectRaw('substring(code, 1, 1) as code1, word');
                        if ($pq_answers->count()) {
                            foreach ($pq_answers->get() as $pq_answer) {
                                $answers[$place->id][$category->name][$concept->name][$pq_answer->code1][] = $pq_answer->word;
                            }
                            foreach ($answers[$place->id][$category->name][$concept->name] as $code=>$words) {
                                $answers[$place->id][$category->name][$concept->name][$code] = join('/', $words);
                            }
                        }
                    }
                    
                }
            }
        }
        return [$answers, [], $total_concepts];
    }    
}

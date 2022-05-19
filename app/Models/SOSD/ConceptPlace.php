<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptPlace extends Model
{
    use HasFactory;
    
    protected $table = 'concept_place';
    protected $fillable = ['concept_id', 'place_id', 'code', 'word'];
    
    public $timestamps = false;
    
    /**
     * Get list of unique answers for given places and a question section
     * Calls for cluster map
     * 
     * @param array $place_ids - Identifiers of places
     * @param int $qsection_id - ID of a question section
     * @return array
     */
    public static function getAnswersForPlacesCategory($place_ids, $category_ids=[], $concept_ids=[]) {
        $answers = self::whereIn('place_id', $place_ids);
        if (sizeof($concept_ids)) {
            $answers->whereIn('concept_id', $concept_ids);
        } elseif (sizeof($category_ids)) {
            $answers->whereIn('concept_id', function ($q) use ($category_ids) {
                            $q->select('id')->from('concepts')
                              ->whereIn('concept_category_id',$category_ids);
                        });
        }
        return array_unique($answers->pluck('word')->toArray());
    }
    
}

<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnketaQuestion extends Model
{
    use HasFactory;
    
    protected $table = 'anketa_question';    
    public $timestamps = false;
    
    /**
     * Get list of unique answers for given places and a question section
     * Calls for cluster map
     * 
     * @param array $place_ids - Identifiers of places
     * @param int $qsection_id - ID of a question section
     * @return array
     */
    public static function getAnswersForPlacesQsections($place_ids, $qsection_ids) {
        $answers = self::whereIn('question_id', function ($q) use ($qsection_ids) {
                            $q->select('id')->from('questions')
                              ->whereIn('qsection_id',$qsection_ids);
                        })->whereIn('anketa_id', function ($q) use ($place_ids) {
                            $q->select('id')->from('anketas')
                              ->whereIn('place_id', $place_ids);
                        })->pluck('answer_text')->toArray();
        return array_unique($answers);
    }
}

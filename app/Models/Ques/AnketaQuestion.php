<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnketaQuestion extends Model
{
    use HasFactory;
    
    protected $table = 'anketa_question';    
    public $timestamps = false;
    protected $fillable = ['anketa_id', 'question_id', 'answer_id', 'answer_text'];
    
    /**
     * Get list of unique answers for given places and a question section
     * Calls for cluster map
     * 
     * @param array $place_ids - Identifiers of places
     * @param int $qsection_id - ID of a question section
     * @return array
     */
    public static function getAnswersForPlacesQsections($place_ids, $qsection_ids=[], $question_ids=[]) {
        $answers = self::whereIn('anketa_id', function ($q) use ($place_ids) {
                            $q->select('id')->from('anketas')
                              ->whereIn('place_id', $place_ids);
                        });
        if (sizeof($question_ids)) {
            $answers->whereIn('question_id', $question_ids);
        } elseif (sizeof($qsection_ids)) {
            $answers->whereIn('question_id', function ($q) use ($qsection_ids) {
                            $q->select('id')->from('questions')
                              ->whereIn('qsection_id',$qsection_ids);
                        });
        }
        return array_unique($answers->pluck('answer_text')->toArray());
    }
}

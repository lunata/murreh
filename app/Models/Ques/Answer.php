<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Ques\Question;

class Answer extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['question_id', 'code', 'answer'];
    
    // Has To Many Relations
    use \App\Traits\Relations\BelongsToMany\Anketas;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
    public static function findOrCreate($question_id, $answer, $code) {
        if (!$question_id || !$answer) { return; }
        
        $question = Question::find($question_id);
        if (!$question) { return; }
        
        $answer_obj = Answer::whereQuestionId($question_id)
                        ->whereAnswer($answer)
                        ->orderBy('code')
                        ->first();
        if (!$answer_obj) {
            $answer_obj = Answer::create([
                'question_id' => $question_id,
                'code' => $code ? $code : $question->newCode(), 
                'answer' => $answer]);
        }
        return $answer_obj;
    }
    
    public static function getCodeById($id) {
        $answer = self::find($id);
        if (!$answer) {
            return;
        }
        return $answer->code;
    }
    
    public static function getCodeDotAnswerById($id) {
        $answer = self::find($id);
        if (!$answer) {
            return;
        }
        return $answer->code.'. '.$answer->answer;
    }
    
    public static function getForPlacesQsection($places, $qsection_ids, $question_ids, $with_weight=false, $metric=1) {
        $weights = [];
        $qsections = Qsection::whereIn('id',$qsection_ids)->get();
        $total_questions = 0;

        $answers = [];
        foreach ($qsections as $qsection) {
            $questions = Question::whereQsectionId($qsection->id);
            if ($question_ids) {
                $questions->whereIn('id', $question_ids);
            }
            foreach ($questions->get() as $question) {
                $total_questions += $with_weight ? $question->weight : 1;
                if ($with_weight) {
                    $weights[$qsection->title][$question->question] = $question->weight;
                }
                $available_answers = $question->answers()->pluck('code')->toArray();
                foreach ($places as $place) {
                    $pq_answers = self::where('answers.question_id',$question->id)
                            ->join('anketa_question', 'answers.id', '=', 'anketa_question.answer_id')
                            ->join('anketas', 'anketas.id', '=', 'anketa_question.anketa_id')
                            ->wherePlaceId($place->id)
                            ->pluck('answer_text','code')->toArray();
                    if ($metric == 2) {
                        foreach ($available_answers as $answer) {
                            $answers[$place->id][$qsection->title][$question->question][$answer] = isset($pq_answers[$answer]) ? 1/sizeof($pq_answers) : 0;                        
                        }                        
                    } else {                    
                        $answers[$place->id][$qsection->title][$question->question] = $pq_answers;
                    }
                }
            }
        }
        return [$answers, $weights, $total_questions];
    }    
}

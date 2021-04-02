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
}

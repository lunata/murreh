<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Ques\Answer;

trait Answer
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function answers()
    {
        return $this->belongsToMany(Answer::class, 'anketa_question')
//                    ->withPivot('question_id')->withPivot('answer_text')
                    ->orderBy('code');
    }
}
<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Ques\Question;

trait Questions
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class)
                    ->withPivot('answer_id')->withPivot('answer_text')
                    ->orderBy('id');
    }
}
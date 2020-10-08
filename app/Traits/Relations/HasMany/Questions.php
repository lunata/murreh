<?php namespace App\Traits\Relations\HasMany;

use App\Models\Ques\Question;

trait Questions
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class)
//                    ->withPivot('answer_id')->withPivot('answer')
                    ->orderBy('id');
    }
}
<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Ques\Anketa;

trait Anketas
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function anketas()
    {
        return $this->belongsToMany(Anketa::class, 'anketa_question')
//                    ->withPivot('question_id')
//                    ->withPivot('answer_text')
                    ->orderBy('fond_number');
    }
}
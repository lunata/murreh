<?php namespace App\Traits\Relations\HasMany;

use App\Models\Ques\Anketa;

trait Anketas
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anketas()
    {
        return $this->hasMany(Anketa::class)
//                    ->withPivot('answer_text')
                    ->orderBy('fond_number');
    }
}
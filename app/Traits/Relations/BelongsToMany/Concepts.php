<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\SOSD\Concept;

trait Concepts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany for Place
     */
    public function concepts()
    {
        return $this->belongsToMany(Concept::class, 'concept_place')
                    ->withPivot('code')->withPivot('word');
    }
}
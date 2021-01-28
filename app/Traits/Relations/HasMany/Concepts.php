<?php namespace App\Traits\Relations\HasMany;

use App\Models\SOSD\Concept;

trait Concepts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function concepts()
    {
        return $this->hasMany(Concept::class)
                    ->orderBy('id');
    }
}
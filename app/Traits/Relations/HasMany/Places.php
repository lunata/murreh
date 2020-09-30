<?php namespace App\Traits\Relations\HasMany;

use App\Models\Geo\Place;

trait Places
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany(Place::class)
                    ->orderBy('name_ru');
    }
}
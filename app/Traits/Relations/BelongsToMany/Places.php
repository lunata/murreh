<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Geo\Place;

trait Places
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function places(){
        return $this->belongsToMany(Place::class)
                    ->orderBy('name_ru');
    }    
}
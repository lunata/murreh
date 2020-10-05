<?php namespace App\Traits\Relations\HasMany;

use App\Models\Person\Informant;

trait Informants
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function informants()
    {
        return $this->hasMany(Informant::class)
                    ->orderBy('name_ru');
    }
}
<?php namespace App\Traits\Relations\HasMany;

use App\Models\Geo\District;

trait Districts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function districts()
    {
        return $this->hasMany(District::class)
                    ->orderBy('name_ru');
    }
}
<?php namespace App\Traits\Relations\HasMany;

use App\Models\Person\Recorder;

trait Recorders
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recorders()
    {
        return $this->hasMany(Recorder::class)
                    ->orderBy('name_ru');
    }
}
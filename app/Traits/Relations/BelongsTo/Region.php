<?php namespace App\Traits\Relations\BelongsTo;

trait Region
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo("App\Models\Geo\Region");
    }
}
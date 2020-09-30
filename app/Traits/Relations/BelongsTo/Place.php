<?php namespace App\Traits\Relations\BelongsTo;

trait Place
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo("App\Models\Geo\Place");
    }
}
<?php namespace App\Traits\Relations\BelongsTo;

trait District
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo("App\Models\Geo\District");
    }
}
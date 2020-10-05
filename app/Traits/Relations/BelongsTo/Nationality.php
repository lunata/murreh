<?php namespace App\Traits\Relations\BelongsTo;

trait Nationality
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationality()
    {
        return $this->belongsTo("App\Models\Person\Nationality");
    }
}
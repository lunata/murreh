<?php namespace App\Traits\Relations\BelongsTo;

trait Occupation
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function occupation()
    {
        return $this->belongsTo("App\Models\Person\Occupation");
    }
}
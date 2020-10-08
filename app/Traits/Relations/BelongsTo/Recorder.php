<?php namespace App\Traits\Relations\BelongsTo;

trait Recorder
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recorder()
    {
        return $this->belongsTo("App\Models\Person\Recorder");
    }
}
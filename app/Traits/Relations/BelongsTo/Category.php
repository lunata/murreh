<?php namespace App\Traits\Relations\BelongsTo;

use App\Models\SOSD\ConceptCategory;

trait Category
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conceptCategory()
    {
        return $this->belongsTo(ConceptCategory::class);
    }    
}
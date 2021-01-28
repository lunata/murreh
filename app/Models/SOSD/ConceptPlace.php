<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptPlace extends Model
{
    use HasFactory;
    
    protected $table = 'concept_place';
    protected $fillable = ['concept_id', 'place_id', 'code', 'word'];
    
    public $timestamps = false;
}

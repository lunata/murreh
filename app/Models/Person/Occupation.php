<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getListWithQuantity;
    use \App\Traits\Methods\search;
    use \App\Traits\Methods\urlArgs;
    
    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Recorders;
    use \App\Traits\Relations\HasMany\Informants;
}

<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

class Region extends Model
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
    use \App\Traits\Relations\HasMany\Districts;
    
    // Region __has_many__ Places
/*    
    public function places()
    {
        return $this->hasMany(Place::class);
    }*/
}

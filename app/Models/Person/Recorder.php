<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

class Recorder extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['name_ru', 'nationality_id', 'occupation_id', 'pol'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getListWithQuantity;
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;
    
    // Has To Many Relations
    use \App\Traits\Relations\BelongsTo\Occupation;
    use \App\Traits\Relations\BelongsTo\Nationality;
    
    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Anketas;
    
    public function getOccupationNameAttribute() : String
    {
        $pol = $this->pol;
        $column = "name_ru_".$pol;
        $occupation = $this->occupation;
        if (!$occupation) {
            return '';
        }
        return $occupation->{$column} ?? '';
    }
    
    public function getNationalityNameAttribute() : String
    {
        $pol = $this->pol;
        $column = "name_ru_".$pol;
        $nationality = $this->nationality;
        if (!$nationality) {
            return '';
        }
        return $nationality->{$column} ?? '';
    }
    
    public function toString() {
        $info=[$this->name];
        
        if ($this->nationality) {
            $info[] = $this->nationality_name;
        }
        
        if ($this->occupation) {
            $info[] = $this->occupation_name;
        }
        
        return join(', ', $info);
    }
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('name_ru');
        
        $objs = self::searchStrField($objs, 'name_ru', $url_args['search_name']);
        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        $objs = self::searchIntField($objs, 'nationality_id', $url_args['search_nationality']);
        $objs = self::searchIntField($objs, 'occupation_id', $url_args['search_occupation']);
        
        return $objs;
    }
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name' => $request->input('search_name'),
                    'search_nationality' => (int)$request->input('search_nationality') ? (int)$request->input('search_nationality') : null,
                    'search_occupation' => (int)$request->input('search_occupation') ? (int)$request->input('search_occupation') : null,
                ];
        
        return $url_args;
    }        
}

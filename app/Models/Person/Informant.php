<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\Str;

use App\Models\Geo\Place;

class Informant extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['name_ru', 'birth_date', 'birth_place_id', 'place_id', 'nationality_id', 'occupation_id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getListWithQuantity;
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;
    
    // Has To Many Relations
    use \App\Traits\Relations\BelongsTo\Nationality;
    use \App\Traits\Relations\BelongsTo\Occupation;
    use \App\Traits\Relations\BelongsTo\Place;

    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Anketas;

    /** Gets place, takes into account locale.
     * 
     * Informant belongs_to Place
     * 
     * @return Relationship, Query Builder
     */
    public function birth_place()
    {
        return $this->belongsTo(Place::class);//,'birth_place_id'
    }  
    
    /**
     * Gets full information about informant
     * 
     * i.e. "Калинина Александра Леонтьевна, 1909, Пондала (Pondal), Бабаевский р-н, Вологодская обл."
     * 
     * @param int $lang_id ID of text language for output translation of settlement title, f.e. Pondal
     * 
     * @return String
     */
    public function informantString($lang_id='')
    {
        $info = [];
        
        if ($this->name) {
            $info[0] = $this->name;
        }
        
        if ($this->birth_date) {
            $info[] = $this->birth_date;
        }
        
        if ($this->birth_place) {
            $birth_place = Place::find($this->birth_place_id);
            $info[] = $birth_place->placeString();
        }
        
        return join(', ', $info);
    }    
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('name_ru');

        $objs = self::searchStrField($objs, 'name_ru', $url_args['search_name']);
        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        $objs = self::searchIntField($objs, 'birth_date', $url_args['search_birth']);
        $objs = self::searchIntField($objs, 'birth_place_id', $url_args['search_birth_place']);
        $objs = self::searchIntField($objs, 'place_id', $url_args['search_place']);
        $objs = self::searchIntField($objs, 'nationality_id', $url_args['search_nationality']);
        $objs = self::searchIntField($objs, 'occupation_id', $url_args['search_occupation']);
        
        return $objs;
    }
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'   => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name' => $request->input('search_name'),
                    'search_birth'   => (int)$request->input('search_birth') ? (int)$request->input('search_birth') : null,
                    'search_birth_place'  => (int)$request->input('search_birth_place') ? (int)$request->input('search_birth_place') : null,
                    'search_place'  => (int)$request->input('search_place') ? (int)$request->input('search_place') : null,
                    'search_nationality' => (int)$request->input('search_nationality') ? (int)$request->input('search_nationality') : null,
                    'search_occupation' => (int)$request->input('search_occupation') ? (int)$request->input('search_occupation') : null,
                ];
        
        return $url_args;
    }    
}

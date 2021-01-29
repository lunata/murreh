<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Geo\Place;

class Concept extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'concept_category_id', 'name'];
    
    use \App\Traits\Methods\searchIntField;    
    use \App\Traits\Methods\urlArgs;

    // Belongs To Relations
    use \App\Traits\Relations\BelongsTo\Category;
    
    public function places()
    {
        return $this->belongsToMany(Place::class, 'concept_place');
    }
    
    public function getSectionAttribute() : String
    {
        return trans("sosd.concept_section_".substr($this->concept_category_id, 0,1));
    }    

    public function idInFormat() {
        return str_pad($this->id, 4, "0", STR_PAD_LEFT);
    }

    public static function search(Array $url_args) {
        $objs = self::orderBy('id');
        $recs = self::searchByCategory($objs, $url_args['search_category']);
        $objs = self::searchByName($objs, $url_args['search_name']);
        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        
        return $objs;
    }
    
    public static function searchByCategory($recs, $category_id) {
        if (!$category_id) {
            return $recs;
        }
        return $recs->where('concept_category_id',$category_id);
    }
    
    public static function searchByName($objs, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->where(function($query) use ($search_value) {
                    $query->where('name', 'like', $search_value);
        });
    }
}

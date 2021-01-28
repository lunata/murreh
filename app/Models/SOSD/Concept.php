<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['id', 'concept_category_id', 'name'];
    
    use \App\Traits\Methods\searchIntField;    
    use \App\Traits\Methods\urlArgs;
    
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

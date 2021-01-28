<?php

namespace App\Models\SOSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptCategory extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name'];

    // Has Many Relations
    use \App\Traits\Relations\HasMany\Concepts;
    
    public function getSectionAttribute() : String
    {
        return trans("sosd.concept_section_".substr($this->id, 0,1));
    }    
    
    /** Gets list dropdown form
     * 
     * @return Array [<key> => <value>,..]
     */
    public static function getList()
    {     
        $objs = self::orderBy('id')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $list[$row->id] = $row->id .'. '. $row->name;
        }
        
        return $list;         
    }
}

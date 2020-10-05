<?php namespace App\Traits\Methods;

trait search
{
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;
    
    public static function search(Array $url_args) {
        $objs = self::orderBy('name_ru');
        $objs = self::searchStrField($objs, 'name_ru', $url_args['search_name']);
        $objs = self::searchIntField($objs, 'id', $url_args['search_id']);
        
        return $objs;
    }
}
<?php namespace App\Traits\Methods;

trait searchStrField
{
    public static function searchStrField($objs, $search_field, $search_value) {
        if (!$search_value) {
            return $objs;
        }
        return $objs->where($search_field, 'like', $search_value);
    }
}
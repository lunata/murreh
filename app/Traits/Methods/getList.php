<?php namespace App\Traits\Methods;

trait getList
{
    /** Gets list of objects
     * 
     * @return Array [1=>'Вологодская обл.',..]
     */
    public static function getList()
    {     
        $regions = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($regions as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }    
}
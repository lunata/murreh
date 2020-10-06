<?php namespace App\Traits\Methods;

trait getList
{
    /** Gets list of objects
     * 
     * @return Array [1=>'Вологодская обл.',..]
     */
    public static function getList()
    {     
        $objs = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }    
}
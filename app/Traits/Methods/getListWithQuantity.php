<?php namespace App\Traits\Methods;

trait getListWithQuantity
{
    /** Gets list of objects with quantity of relations $method_name
     * 
     * @return Array [1=>'Вологодская обл. (199)',..]
     */
    public static function getListWithQuantity($method_name)
    {     
        $objs = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($objs as $row) {
            $count=$row->$method_name()->count();
            $name = $row->name;
            if ($count) {
                $name .= " ($count)";
            }
            $list[$row->id] = $name;
        }
        
        return $list;         
    }
    
}
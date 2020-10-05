<?php namespace App\Traits\Methods;

trait getNameAttribute
{
    /** Gets name of this object, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        $column = "name_ru";
        $name = $this->{$column};
        return $name;
    }
}
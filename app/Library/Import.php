<?php

namespace App\Library;

use App\Models\Geo\Place;

/**
 */
class Import {
    public static function placeCoord($lines) {
        foreach ($lines as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            if (!preg_match("/^(\d+)\.\s+(\d+\.\d+),\s+(\d+\.\d+)$/", $line, $regs)) {
                dd("Неправильный разбор строки ".$line);
            }
            self::writePlaceCoord($regs);
        }        
    } 
    
    public static function writePlaceCoord($data) {
        $place = Place::find($data[1]);
        if (!$place) {
            dd("Населенный пункт с ID=".$data[1]." отсутствует.");
        }
        $place->fill(['latitude'=>$data[2], 'longitude'=>$data[2]])->save();
    }
}

<?php

namespace App\Library;

class Map
{
    public static function markers($with_names=false) {
        $markers = [
            'red' => 'красный',
            'orange' => 'оранжевый',
            'green' => 'зелёный',
            'skyblue' => 'небесно-голубой',
            'magenta' => 'малиновый',
            'brown' => 'коричневый',
            'blue' => 'синий',
            'olive' => 'оливковый',
            'purple' => 'пурпурный',
            'teal' => 'бирюзовый',
            'pink' => 'розовый',
            'sandy' => 'песочный',
            'lime' => 'лимонный',
            'cyan' => 'голубой',
            'violet' => 'фиолетовый',
            'coral' => 'коралловый',
            'white' => 'белый',
            'grey' => 'серый',
            'darkgrey' => 'тёмно-серый',
            'black' => 'чёрный'];
        return $with_names ? $markers : array_keys($markers);
    }
}

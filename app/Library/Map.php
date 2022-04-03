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
    
    public static function getHexColor($color_name) {
        $markers = [
            'red' => '#ee3922',
            'orange' => '#ff961d',
            'green' => '#45ad00',
            'skyblue' => '#00a9dd',
            'magenta' => '#f051bc',
            'brown' => '#b92e32',
            'blue' => '#0065a2',
            'olive' => '#6b830e',
            'purple' => '#63356b',
            'teal' => '#226878',
            'pink' => '#ff8e7e',
            'sandy' => '#ffcb8e',
            'lime' => '#9cf667',
            'cyan' => '#49daff',
            'violet' => '#ff91ed',
            'coral' => '#ff7e80',
            'white' => '#ede7e7',//ffffff',
            'grey' => '#a5a5a5',
            'darkgrey' => '#565656',
            'black' => '#2b2b2b'];
        return isset($markers[$color_name]) ? $markers[$color_name] : '';
    }
}

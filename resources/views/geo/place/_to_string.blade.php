<?php
        $info=[$place->name];
/*        $names = [];
        foreach (['name_old_ru', 'name_krl', 'name_old_krl'] as $n) {
            if ($place->{$n}) {
                $names[] = $place->{$n};
            }
        }
        if (sizeof ($names)) {
            $info[0] .= " (".join(', ', $names).")";
        }*/
        if ($place->districtNamesWithDates()) {
            $info[] = $place->districtNamesWithDates();
        }
        
        print join(', ', $info);


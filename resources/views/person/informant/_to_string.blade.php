<?php
        $info = [];
        if ($informant) {
            if ($informant->name) {
                $info[] = $informant->name;
            }

            if ($informant->nationality) {
                $info[] = $informant->nationality_name;
            }

            if ($informant->occupation) {
                $info[] = $informant->occupation_name;
            }

            if ($informant->birth_date) {
                $info[] = $informant->birth_date. ' <i>'.\Lang::get('person.birth_year'). '</i>';
            }

            if ($informant->birth_place) {
                $info[] = '<i>'.\Lang::get('person.nee'). '</i> '. $informant->birth_place->toStringWithDistrict();
            }

            if ($informant->place) {
                $info[] = '<i>'.\Lang::get('person.live'). '</i> '. $informant->place->toStringWithDistrict();
            }
        }
        $informant_info = join(', ', $info);
?>
@if ($informant_info)
<p><b>{{ trans('person.informant')}}:</b> 
    {!! $informant_info !!}

</p>
@endif
<?php
        $info = [];
        
        if ($informant->name) {
            $info[] = $informant->name;
        }
        
        if ($informant->birth_date) {
            $info[] = '<i>'.\Lang::get('person.birth_year'). '</i> '. $informant->birth_date;
        }

        $informant_info = join(', ', $info);
?>
@if ($informant_info)
<i>{{ trans('person.informant')}}:</i> 
    {!! $informant_info !!},

    @if ($informant->birth_place)
    <i>{{ trans('person.nee')}}</i> @include('geo.place._to_string',['place' => $informant->birth_place, 'lang_id' => $lang_id])@endif
@endif
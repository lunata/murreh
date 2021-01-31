@foreach ($concepts[$category_id] as $concept)
<?php $words = $place->wordListByConceptToString($concept->id);?>
            <p>
                <span class='{{$words ? '': 'warning'}}'>
                {{$concept->idInFormat()}} - {{$concept->name}}</span>: 
                <b>{{$words}}</b>
            </p>
@endforeach

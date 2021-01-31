            @foreach ($concepts[$category_id] as $concept)
            <p>
                {{$concept->idInFormat()}} - {{$concept->name}}: 
                <b>{{$place->wordListByConceptToString($concept->id)}}</b>
            </p>
            @endforeach

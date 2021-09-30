@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concepts') }}
@stop

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
 @stop

@section('body')
<h2>{{$concept->concept_category_id}}. 
{{$concept->name}}</h2>
@foreach ($markers as $code => $color)
<img src="/images/markers/marker-icon-{{$color}}.png"> {{$code}} - 
{{join(', ', array_unique($concept->places()->where('code', 'like', $code.'%')->pluck('word')->toArray()))}} 
({{sizeof(array_unique($code_places[$code]))}})<br>
@endforeach
<div id="mapid" style="width: 100%; min-width: 750px; height: 2100px;"></div>
@stop

@section('footScriptExtra')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    
<script>
    var mymap = L.map('mapid').setView([61.8, 33.9], 7);
@foreach ($markers as $code => $color)
    var {{$code}}Icon = new L.Icon({
      iconUrl: '/images/markers/marker-icon-{{$color}}.png',
      iconAnchor: [12, 41],
    });
@endforeach    
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1
    }).addTo(mymap);

@foreach ($code_places as $code => $places)
    @foreach ($places as $place)
    <?php
/*        $anketa_count = $place->anketas()->count();
        $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$place->id.">".$anketa_count." ".
                trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a>" : '';*/
    ?>
    L.marker([{{$place->latitude}}, {{$place->longitude}}], {icon: {{$code}}Icon}).addTo(mymap)
            .bindPopup("<b>{{$place->name_ru}}</b>{{--{!!$anketa_link!!}--}}").openPopup();
    @endforeach
@endforeach
</script>
@stop

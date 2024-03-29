@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas_on_map') }}
@stop

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
 @stop

@section('body')
<div id="mapid" style="width: 100%; min-width: 750px; height: 2100px;"></div>
@stop

@section('footScriptExtra')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    
<script>
	var mymap = L.map('mapid').setView([61.8, 35], 7);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1
        }).addTo(mymap);

        @foreach ($places as $place)
        <?php
            $anketa_count = $place->anketas()->count();
            $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$place->id.">".$anketa_count." ".
                    trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a>" : '';
        ?>
	L.marker([{{$place->latitude}}, {{$place->longitude}}]).addTo(mymap)
		.bindPopup("<b>{{$place->name_ru}}</b>{!!$anketa_link!!}").openPopup();
        @endforeach
</script>
@stop

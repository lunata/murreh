<?php // 
    $start_x = 2950; 
    $start_y = 5550; 
    $r = 5;
    $height=2100; 
?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.places') }}
@stop

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
    {!!Html::style('css/map.css')!!}   
 @stop

@section('body')
<div id="mapid" style="width: 850px; height: {{$height}}px;"></div>

<!--canvas id="canvas" width="850" height="{{$height}}"></canvas-->
@stop

@section('footScriptExtra')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    
<script>

	var mymap = L.map('mapid').setView([61.8, 34], 7);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(mymap);

        @foreach ($places as $place)
	L.marker([{{$place->latitude}}, {{$place->longitude}}]).addTo(mymap)
		.bindPopup("<b>{{$place->name_ru}}</b>").openPopup();
        @endforeach
/*
	L.circle([51.508, -0.11], 500, {
		color: 'red',
		fillColor: '#f03',
		fillOpacity: 0.5
	}).addTo(mymap).bindPopup("I am a circle.");

	L.polygon([
		[51.509, -0.08],
		[51.503, -0.06],
		[51.51, -0.047]
	]).addTo(mymap).bindPopup("I am a polygon.");*/


	var popup = L.popup();

	function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent("You clicked the map at " + e.latlng.toString())
			.openOn(mymap);
	}

	mymap.on('click', onMapClick);

</script>
@stop

@section('jqueryFunc')
/*      var canvas = document.getElementById('canvas');
      if (canvas.getContext) {
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = 'green';
        var dots = [];
        
        @foreach ($places as $place)
        var circle{{$place->id}} = new Path2D();
        <?php $x=(int)(100*($place->longitude)-$start_x);
              $y=$height-(int)(100*($place->latitude)-$start_y); ?>
        circle{{$place->id}}.arc({{$x}}, {{$y}}, {{$r}}, 0, 2 * Math.PI, false);
        ctx.fill(circle{{$place->id}});
        @endforeach
      }*/
@stop

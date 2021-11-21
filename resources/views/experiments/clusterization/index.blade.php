@extends('layouts.page')

@section('page_title')
{{ trans('navigation.clusterization') }}
@endsection

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
 @stop

@section('body')
    <h2>{{$qsection->title}}</h2>
    <h3>Ответы</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th>Населенный пункт</th>
        @foreach (array_keys($answers[array_key_first($answers)]) as $question)
            <th>{{$question}}</th>
        @endforeach
        </tr>
        
        @foreach ($answers as $place_id => $questions)
        <tr>
            <th style="text-align: left">{{$place_names[$place_id]}}</th>
        @foreach (array_values($questions) as $code)
            <td>{{join(', ', $code)}}</td>
        @endforeach
        </tr>
        @endforeach
    </table>
    
    <h3 style="margin-top: 20px">Различия в ответах</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th></th>
            <th></th>
{{--        @foreach (array_values($place_names) as $place_name)
            <th>{{$place_name}}</th>
        @endforeach--}}
        @foreach (array_keys($place_names) as $place_id)
            <th>{{$place_id}}</th>
        @endforeach
        </tr>
        
        @foreach ($differences as $place_id => $place_diff)
        <tr>
            <th style="text-align: right">{{$place_id}}</th>
            <th style="text-align: left">{{$place_names[$place_id]}}</th>
        @foreach (array_values($place_diff) as $d)
            <td>{{$d}}</td>
        @endforeach
        </tr>
        @endforeach
    </table>
    
    <h3 style="margin-top: 20px">Кластеризация <a href="https://ru.wikipedia.org/wiki/%D0%9C%D0%B5%D1%82%D0%BE%D0%B4_%D0%BF%D0%BE%D0%BB%D0%BD%D0%BE%D0%B9_%D1%81%D0%B2%D1%8F%D0%B7%D0%B8">методом полной связи</a></h3>
    <P>Расстояние между кластерами не больше {{$clusterization_limit}}</P>
{{--    @foreach ($clusters as $step => $step_clusters) --}}
    <h4>Шаг {{$last_step}}</h4>
        @foreach ($clusters[$last_step] as $cl_num => $cluster) 
        <P><b>{{$cl_num}}</b>: {{\App\Models\Geo\Place::namesByIdsToString($cluster)}}</P>
        @endforeach
{{--    @endforeach --}}
    
@include('/experiments/clusterization/_map')
@endsection

@section('footScriptExtra')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    
<script>
    var mymap = L.map('mapid').setView([61.8, 33.9], 7);
@foreach ($markers as $cluster_num => $color)
    var c{{$cluster_num}}Icon = new L.Icon({
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

@foreach ($cluster_places as $cluster_num => $places)
    @foreach ($places as $place)
    <?php
        $anketa_count = $place->anketas()->count();
        $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$place->id.">".$anketa_count." ".
                trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a>" : '';
    ?>
    L.marker([{{$place->latitude}}, {{$place->longitude}}], {icon: c{{$cluster_num}}Icon}).addTo(mymap)
            .bindPopup("<b>{{$place->name_ru}}</b>{!!$anketa_link!!}").openPopup();
    @endforeach
@endforeach
</script>
@endsection

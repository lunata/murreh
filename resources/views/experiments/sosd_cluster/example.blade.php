@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
     {!!Html::style('css/markers.css')!!}
 @stop

@section('body')
    <h2>Метод {{$method_title}}</h2>
    <h3>Раздел{{sizeof($qsections)>1 ? 'ы' : ''}}: {{join(', ', $qsections)}}</h3>
{{----}}    
@if ($method_id==2)
    @foreach ($clusters as $step => $step_clusters) 
        @if ($step > 1 && $step != $last_step)
    <h4>Шаг {{$step}}, 
        количество кластеров: {{sizeof($clusters[$step])}}
    </h4> 
            @foreach ($clusters[$step] as $cl_num => $cluster) 
    <p>
        <b>{{$cl_num}}</b> ({{sizeof($cluster)}}): {{\App\Models\Geo\Place::namesByIdsToString($cluster)}}
        <br><span style="font-style: italic; color:grey">{{join(', ', \App\Models\Ques\AnketaQuestion::getAnswersForPlacesQsections($cluster, $qsection_ids, $question_ids))}}</span>
    </p>        
            @endforeach
        @endif
    @endforeach 
@endif    
{{----}}
    
    <h4>Шаг {{$last_step}}, 
        количество кластеров: {{sizeof($clusters[$last_step])}},
        минимальное расстояние между кластерами: {{$min_cl_distance}}
    </h4>
        @foreach ($clusters[$last_step] as $cl_num => $cluster) 
    <div class="cluster-info">
        <div class="cluster-marker">
            <div class="marker-icon marker-legend marker-{{$cl_colors[$cl_num]}}">
            <!--img src="/images/markers/marker-icon-{{$cl_colors[$cl_num]}}.png" style="padding-right: 5px; margin-top:-10px"-->
            <span><b>{{$cl_num}}</b> ({{sizeof($cluster)}}):</span>
            </div>
        <div>
       {{\App\Models\Geo\Place::namesWithDialectsByIdsToString($cluster)}}
        </div>
        </div>
    </div>
        @endforeach
    
    @include('widgets.leaflet.map', ['markers'=>[], 'height'=> 2200])
    
    @if ($dendrogram)
    <img src="{{'/storage/'.$dendrogram_file}}">
    @endif
@endsection

@section('footScriptExtra')
    @include('widgets.leaflet.map_script', ['places'=>$cluster_places, 'colors'=>array_values($cl_colors), 'latitude'=>62])
    {!!Html::script('js/experiment.js')!!}
@endsection

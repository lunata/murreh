@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
    {!!Html::style('css/select2.min.css')!!}
 @stop

@section('body')
    @include('experiments.anketa_cluster._search_form') 

    <p><a href="/experiments/anketa_cluster/view_data?normalize={{$normalize}}&with_weight={{$with_weight}}&{{http_build_query(['qsection_ids'=>$qsection_ids])}}&{{http_build_query(['question_ids'=>$question_ids])}}&{{http_build_query(['place_ids'=>$place_ids])}}">Посмотреть данные</a></p>
    @if (User::checkAccess('admin'))
        <p><a href="/experiments/anketa_cluster/export_data_for_dendrogram?normalize={{$normalize}}&with_weight={{$with_weight}}&{{http_build_query(['qsection_ids'=>$qsection_ids])}}&{{http_build_query(['question_ids'=>$question_ids])}}&{{http_build_query(['place_ids'=>$place_ids])}}">Выгрузить матрицу расстояний</a></p>
    @endif
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
            <img src="/images/markers/marker-icon-{{$cl_colors[$cl_num]}}.png" style="padding-right: 5px; margin-top:-10px">
            @include('widgets.form.formitem._select', 
                    ['name' => "cl_colors[$cl_num]", 
                     'values' => $color_values,
                     'value' => $cl_colors[$cl_num]])                                              
            <span><b>{{$cl_num}}</b> ({{sizeof($cluster)}}):</span>
        </div>
       {{\App\Models\Geo\Place::namesWithDialectsByIdsToString($cluster)}}
       <br><span style="font-style: italic; color:grey">{{join(', ', \App\Models\Ques\AnketaQuestion::getAnswersForPlacesQsections($cluster, $qsection_ids, $question_ids))}}</span>
    </div>
        @endforeach

    {!! Form::close() !!}
    
    @include('widgets.leaflet.map', ['markers'=>[]])
@endsection

@section('footScriptExtra')
    @include('widgets.leaflet.map_script', ['places'=>$cluster_places, 'colors'=>array_values($cl_colors)])
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
    {!!Html::script('js/experiment.js')!!}
@endsection

@section('jqueryFunc')
    selectQsection();    
    selectQuestion('qsection_ids');    
    selectPlace();    
    selectAllFields('select-all-place', '.place-values input');
    for (i=4; i<7; i++) {
        selectAllFields('select-places-'+i, '.places-'+i);
    }
@stop
